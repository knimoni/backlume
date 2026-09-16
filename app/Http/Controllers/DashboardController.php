<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $totalGames = $user->userGames()->count();

        $playingGames = $user->userGames()
            ->where('status', 'playing')
            ->count();

        $completedGames = $user->userGames()
            ->where('status', 'completed')
            ->count();

        $backlogGames = $user->userGames()
            ->where('status', 'backlog')
            ->count();

        $droppedGames = $user->userGames()
            ->where('status', 'dropped')
            ->count();

        return view('dashboard', [
            'user' => $user,
            'totalGames' => $totalGames,
            'playingGames' => $playingGames,
            'completedGames' => $completedGames,
            'backlogGames' => $backlogGames,
            'droppedGames' => $droppedGames,
        ]);
    }
}
