<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function logo() {
        return $this->morphOne(Image::class, 'owner');
    }
}
