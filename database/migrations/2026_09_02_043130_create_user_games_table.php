<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_games', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('game_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('playtime_minutes')->default(0);

            $table->enum('status', [
                'backlog',
                'playing',
                'completed',
                'dropped'
            ])->default('backlog');

            $table->unsignedTinyInteger('rating')->nullable();

            $table->text('review')->nullable();

            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'game_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_games');
    }
};