<?php

namespace App\Http\Controllers;

use App\Models\UserGame;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function show(UserGame $userGame)
    {
        abort_unless($userGame->user_id === Auth::id(), 403);

        $userGame->load('game');

        return view('games.show', [
            'userGame' => $userGame,
        ]);
    }
    public function edit(UserGame $userGame)
    {
        abort_unless($userGame->user_id === Auth::id(), 403);

        $userGame->load('game');

        return view('games.edit', [
            'userGame' => $userGame,
        ]);
    }

    public function update(Request $request, UserGame $userGame): RedirectResponse
    {
        abort_unless($userGame->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:backlog,playing,completed,dropped'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'review' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $userGame->update($validated);

        return redirect()
            ->route('library.edit', $userGame)
            ->with('success', 'Game berhasil diperbarui.');
    }
    public function destroy(UserGame $userGame): RedirectResponse
    {
        abort_unless($userGame->user_id === Auth::id(), 403);

        $userGame->delete();

        return redirect()
            ->route('library')
            ->with('success', 'Game berhasil dihapus dari library.');
    }
}
