<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight',
        'time_of_day',
        'logged_at',
        'bmi',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBmiAttribute()
    {
        $user = $this->user;

        if ($user === null || $this->weight === null) {
            return null;
        }

        $heightInMeters = $user->healthData ? $user->healthData->height / 100 : null;

        if ($heightInMeters && $heightInMeters > 0) {
            return round($this->weight / ($heightInMeters ** 2), 2);
        }

        return null;
    }

}
