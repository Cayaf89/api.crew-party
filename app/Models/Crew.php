<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function getLogo() {
        return !empty($this->logo) ? '/storage/' . $this->logo : NULL;
    }

    public function setLogoAttribute($logo) {
        $img = Image::make($logo)->fit(400);

        $filename  = 'crew/' . uniqid() . time();

        Storage::disk('public')->put($filename, $img->encode());

        if (!empty($this->logo)) {
            Storage::disk('public')->delete($this->logo);
        }

        $this->attributes['logo'] = $filename;
    }
}
