<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $table = 'crew';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'owner_id'
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
