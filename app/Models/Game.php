<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'steam_app_id',
        'name',
        'image_url',
    ];

    public function userGames(): HasMany
    {
        return $this->hasMany(UserGame::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_games')
            ->withPivot([
                'playtime_minutes',
                'status',
                'rating',
                'review',
                'started_at',
                'completed_at',
            ])
            ->withTimestamps();
    }
}