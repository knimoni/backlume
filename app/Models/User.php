<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'email', 'password', 'steam_id'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userGames(): HasMany
    {
        return $this->hasMany(UserGame::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'user_games')
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
