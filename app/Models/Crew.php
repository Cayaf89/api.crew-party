<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    // Roles
    const ADMIN = 'ADMIN';
    const MEMBER = 'MEMBER';

    // Status
    const JOINED = 'JOINED';
    const PENDING = 'PENDING';

    protected $table = 'crew';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function events(): HasMany {
        return $this->hasMany(Event::class, 'crew_id');
    }

    public function logo(): MorphOne {
        return $this->morphOne(Image::class, 'owner');
    }
}
