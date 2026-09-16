<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = $user->userGames()
            ->with('game');

        $query = $user->userGames()
            ->with('game');

        if ($request->filled('status')) {
            $status = $request->status;

            if (in_array($status, ['backlog', 'playing', 'completed', 'dropped'])) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('game', function ($gameQuery) use ($request) {
                $gameQuery->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $games = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('library', [
            'user' => $user,
            'games' => $games,
        ]);
    }
}
