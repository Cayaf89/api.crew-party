<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @property integer $id
 * @property integer $owner_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property User[] $users
 * @property User $owner
 * @property Event[] $events
 */
class Crew extends Model
{

    use HasFactory;

    protected $table = 'crew';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'logo',
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function events() {
        return $this->hasMany(Event::class, 'crew_id');
    }

    public function getLogo() {
        return !empty($this->logo) ? '/storage/' . $this->logo : NULL;
    }

    public function setLogoAttribute($logo) {
        $img = Image::make($logo)->fit(400);

        $filename = 'crew/' . uniqid() . time();

        Storage::disk('public')->put($filename, $img->encode());

        if (!empty($this->logo) && $this->logo !== 'crew/default-logo.png') {
            Storage::disk('public')->delete($this->logo);
        }

        $this->attributes['logo'] = $filename;
    }
}
