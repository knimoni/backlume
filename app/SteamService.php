<?php

namespace App;

use Illuminate\Support\Facades\Http;

class SteamService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.steam.api_key');
    }

    public function getOwnedGames(string $steamId): ?array
    {
        $response = Http::get(
            'https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/',
            [
                'key' => $this->apiKey,
                'steamid' => $steamId,
                'format' => 'json',
                'include_appinfo' => 1,
                'include_played_free_games' => 1,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }
    public function getAppDetails(int $appId): ?array
    {
        $response = Http::get(
            'https://store.steampowered.com/api/appdetails',
            [
                'appids' => $appId,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $result = $response->json();

        if (
            !isset($result[$appId]) ||
            $result[$appId]['success'] !== true
        ) {
            return null;
        }

        return $result[$appId]['data'];
    }
    public function searchApps(string $term): array
    {
        $response = Http::get(
            'https://store.steampowered.com/api/storesearch/',
            [
                'term' => $term,
                'l' => 'english',
                'cc' => 'us',
            ]
        );

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();

        if (!isset($data['items'])) {
            return [];
        }

        $results = [];

        foreach ($data['items'] as $item) {
            if (!isset($item['id'], $item['name'])) {
                continue;
            }

            $details = $this->getAppDetails((int) $item['id']);

            if (!$details) {
                continue;
            }

            if (($details['type'] ?? null) !== 'game') {
                continue;
            }

            $results[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'tiny_image' => $item['tiny_image'] ?? null,
            ];
        }

        return $results;
    }
    public function getMockOwnedGames(): array
    {
        return [
            [
                'appid' => 250900,
                'name' => 'The Binding of Isaac: Rebirth',
                'playtime_forever' => 120,
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/250900/header.jpg',
            ],
            [
                'appid' => 632360,
                'name' => 'Risk of Rain 2',
                'playtime_forever' => 350,
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/632360/header.jpg',
            ],
            [
                'appid' => 601150,
                'name' => 'Devil May Cry 5',
                'playtime_forever' => 600,
                'image_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/601150/header.jpg',
            ],
        ];
    }
}
