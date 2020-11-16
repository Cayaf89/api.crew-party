<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @property integer $id
 * @property integer $crew_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $description
 * @property string $cover
 * @property Crew $crew
 * @property EventChoice[] $eventChoices
 */
class Event extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'event';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['crew_id', 'created_at', 'updated_at', 'name', 'description', 'cover'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventChoices()
    {
        return $this->hasMany(EventChoice::class);
    }

    public function getCover() {
        return !empty($this->cover) ? '/storage/' . $this->cover : NULL;
    }

    public function setCoverAttribute($cover) {
        $img = Image::make($cover)->fit(2400, 900);

        $filename = 'event/' . uniqid() . time();

        Storage::disk('public')->put($filename, $img->encode());

        if (!empty($this->cover) && $this->cover !== 'event/default-cover.jpg') {
            Storage::disk('public')->delete($this->cover);
        }

        $this->attributes['cover'] = $filename;
    }
}
