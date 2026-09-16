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

        if (count($games) === 0) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Library Steam kamu tidak memiliki game yang dapat di-import.'
                );
        }

        $now = now();

        $gameRows = [];

        foreach ($games as $steamGame) {
            $gameRows[] = [
                'steam_app_id' => $steamGame['appid'],
                'name' => $steamGame['name'],
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/' . $steamGame['appid'] . '/header.jpg',
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }

        Game::upsert(
            $gameRows,
            ['steam_app_id'],
            ['name', 'image_url', 'updated_at']
        );

        $steamAppIds = collect($games)
            ->pluck('appid')
            ->all();

        $gameIds = Game::whereIn('steam_app_id', $steamAppIds)
            ->pluck('id', 'steam_app_id');

        $userGameRows = [];

        foreach ($games as $steamGame) {
            $userGameRows[] = [
                'user_id' => $user->id,
                'game_id' => $gameIds[$steamGame['appid']],
                'playtime_minutes' => $steamGame['playtime_forever'] ?? 0,
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }

        UserGame::upsert(
            $userGameRows,
            ['user_id', 'game_id'],
            ['playtime_minutes', 'updated_at']
        );

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
