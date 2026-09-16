<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\UserGame;
use App\SteamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SteamController extends Controller
{
    public function addToLibrary(int $steamAppId)
    {
        $steam = app(SteamService::class);
        $user = Auth::user();

        $gameData = $steam->getAppDetails($steamAppId);

        if (!$gameData || ($gameData['type'] ?? null) !== 'game') {
            abort(404);
        }

        $game = Game::updateOrCreate(
            [
                'steam_app_id' => $steamAppId,
            ],
            [
                'name' => $gameData['name'],
                'image_url' => $gameData['header_image'] ?? null,
            ]
        );

        UserGame::firstOrCreate(
            [
                'user_id' => $user->id,
                'game_id' => $game->id,
            ],
            [
                'playtime_minutes' => 0,
                'status' => 'backlog',
            ]
        );

        return redirect()
            ->route('library')
            ->with('success', $game->name . ' berhasil ditambahkan ke library.');
    }

    public function import()
    {
        $steam = app(SteamService::class);
        $user = Auth::user();

        $games = $steam->getOwnedGames($user->steam_id);

        if ($games === null) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Library Steam tidak dapat diambil. Pastikan profil Steam dan detail game kamu tidak private.'
                );
        }

        foreach ($games as $steamGame) {
            $game = Game::updateOrCreate(
                [
                    'steam_app_id' => $steamGame['appid'],
                ],
                [
                    'name' => $steamGame['name'],
                    'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/' . $steamGame['appid'] . '/header.jpg',
                ]
            );

            UserGame::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                ],
                [
                    'playtime_minutes' => $steamGame['playtime_forever'] ?? 0,
                ]
            );
        }

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                count($games) . ' game berhasil di-import dari Steam.'
            );
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'term' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $steam = app(SteamService::class);

        $results = $steam->searchApps($request->term);

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    public function searchPage(Request $request)
    {
        $term = $request->query('term', '');
        $results = [];

        if ($term !== '') {
            $request->validate([
                'term' => ['string', 'min:2', 'max:100'],
            ]);

            $steam = app(SteamService::class);
            $results = $steam->searchApps($term);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $libraryAppIds = $user->games()
                ->pluck('steam_app_id')
                ->all();

            foreach ($results as &$game) {
                $game['in_library'] = in_array(
                    $game['id'],
                    $libraryAppIds
                );
            }

            unset($game);
        }

        return view('steam.games', [
            'results' => $results,
            'term' => $term,
        ]);
    }
}
