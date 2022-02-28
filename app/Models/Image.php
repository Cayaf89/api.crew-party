<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property integer $id
 * @property integer $owner_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $description
 * @property string $url
 * @property User[] $users
 * @property User $owner
 * @property Event[] $events
 */
class Image extends Model
{

    use HasFactory;

    const TYPES = [
        'user' => 'App\Models\User',
        'crew' => 'App\Models\Crew',
        'event' => 'App\Models\Event',
    ];

    protected $table = 'image';

    protected $primaryKey = 'id';

    protected $fillable = [
        'owner_type',
        'owner_id',
        'url',
    ];

    public function owner(): MorphTo {
        return $this->morphTo();
    }

    public function getTypeSlug($typeClass) {
        return array_search($typeClass, self::TYPES);
    }

    public function getTypeClass($typeSlug) {
        return array_search($typeSlug, self::TYPES);
    }

    public function getUrl(): ?string {
        return !empty($this->url) ? '/storage/' . $this->url : NULL;
    }

    public function setUrlAttribute($url) {
        $img = \Intervention\Image\Facades\Image::make($url);
        $type = $this->getTypeSlug($this->owner_type);

        $filename = 'images/' . $type . '/' . uniqid() . time();

        Storage::disk('public')->put($filename, $img->encode());

        if (!empty($this->url) && $this->url !== 'images/default-logo.png') {
            Storage::disk('public')->delete($this->url);
        }

        $this->attributes['url'] = $filename;
    }

    public function delete(): ?bool {
        if (!empty($this->url) && $this->url !== 'images/default-logo.png') {
            Storage::disk('public')->delete($this->url);
        }
        return parent::delete();
    }
}
