<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    /**
     * Get the owning lockable model.
     */
    public function lockable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that created the lock.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable. This should include all the standard
     * attributes of this model, excluding auto-generated ones like 'id', 'created_at' etc.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'lockable_id',
        'lockable_type',
    ];
}
