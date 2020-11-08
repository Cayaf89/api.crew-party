<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $event_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $date
 * @property Event $event
 * @property User[] $users
 */
class EventChoice extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'event_choice';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['event_id', 'created_at', 'updated_at', 'date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function countUsers() {
        return $this->users()->count();
    }
}
