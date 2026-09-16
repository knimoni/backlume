<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGame extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'playtime_minutes',
        'status',
        'rating',
        'review',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    public function getFormattedPlaytimeAttribute(): string
    {
        $hours = intdiv($this->playtime_minutes, 60);
        $minutes = $this->playtime_minutes % 60;

        if ($hours === 0) {
            return "{$minutes}m";
        }

        if ($minutes === 0) {
            return "{$hours}h";
        }

        return "{$hours}h {$minutes}m";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
