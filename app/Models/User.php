<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'logo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function crews() {
        return $this->belongsToMany(Crew::class);
    }

    public function eventChoices() {
        return $this->belongsToMany(EventChoice::class);
    }

    public function myCrews() {
        return $this->hasMany(Crew::class, 'owner_id');
    }

    public function getLogo() {
        return !empty($this->logo) ? '/storage/' . $this->logo : NULL;
    }

    public function setLogoAttribute($logo) {
        $img = Image::make($logo)->fit(400);

        $filename  = 'user/' . uniqid() . time();

        Storage::disk('public')->put($filename, $img->encode());

        if (!empty($this->logo)) {
            Storage::disk('public')->delete($this->logo);
        }

        $this->attributes['logo'] = $filename;
    }
}
