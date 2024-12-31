<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'height',
        'weight_goal',
    ];

    /**
     * Relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter health data for a specific user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Accessor to format height (optional).
     */
    public function getFormattedHeightAttribute()
    {
        return number_format($this->height, 2) . ' m';
    }
}
