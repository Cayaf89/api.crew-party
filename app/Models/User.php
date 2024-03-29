<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property integer $id
 * @property integer $owner_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $logo
 * @property Crew[] $crews
 * @property Crew[] $myCrews
 * @property EventChoice[] $eventChoices
 */
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

    public function myCrews(): HasMany {
        return $this->hasMany(Crew::class, 'owner_id');
    }

    public function crews(): BelongsToMany {
        return $this->belongsToMany(Crew::class)
                    ->withPivot('status', 'role')
                    ->withTimestamps('created_at', 'updated_at');
    }

    public function isInCrew($crewId): bool {
        return $this->crews()->where('id', $crewId)->exists();
    }

    public function isAdminOfCrew($crew_id): bool {
        return $this->crews()
                    ->where('id', $crew_id)
                    ->where('status', Crew::JOINED)
                    ->where('role', Crew::ADMIN)
                    ->exists();
    }

    public function eventChoices(): BelongsToMany {
        return $this->belongsToMany(EventChoice::class);
    }

    public function logo(): MorphOne {
        return $this->morphOne(Image::class, 'owner');
    }
}
