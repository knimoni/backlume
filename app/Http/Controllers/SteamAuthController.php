<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SteamAuthController extends Controller
{
    public function redirectToSteam(): RedirectResponse
    {
        $params = [
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.return_to' => route('steam.callback'),
            'openid.realm' => config('app.url'),
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        return redirect()->away(
            'https://steamcommunity.com/openid/login?' . http_build_query($params)
        );
    }

    public function handleCallback(Request $request)
    {
        // Ambil parameter Claimed ID dari callback Steam.
        $claimedId = $request->query('openid_claimed_id');

        // Debug sementara untuk melihat parameter yang benar-benar diterima Laravel.
        if (!$claimedId) {
            return response()->json([
                'message' => 'SteamID tidak ditemukan.',
                'query_keys' => array_keys($request->query()),
                'claimed_id_underscore' => $request->query('openid_claimed_id'),
                'claimed_id_dotted' => $request->query('openid.claimed_id'),
                'all_query' => $request->query(),
            ]);
        }

        // Ambil semua parameter callback dan ubah kembali
        // dari format Laravel menjadi format OpenID Steam.
        $openidParams = [];

        foreach ($request->query() as $key => $value) {
            $openidKey = str_replace('_', '.', $key);
            $openidParams[$openidKey] = $value;
        }

        // Minta Steam memverifikasi respons OpenID.
        $openidParams['openid.mode'] = 'check_authentication';

        $response = Http::asForm()->post(
            'https://steamcommunity.com/openid/login',
            $openidParams
        );

        // Steam harus menjawab is_valid:true.
        if (
            !$response->successful() ||
            !preg_match('/is_valid\s*:\s*true/i', $response->body())
        ) {
            return 'Login Steam tidak valid.<br><br>'
                . 'Respons Steam:<pre>'
                . e($response->body())
                . '</pre>';
        }

        // SteamID dari Claimed ID.
        $prefix = 'https://steamcommunity.com/openid/id/';

        if (!str_starts_with($claimedId, $prefix)) {
            return 'Claimed ID Steam tidak valid.';
        }

        $steamId = substr($claimedId, strlen($prefix));

        if (!ctype_digit($steamId)) {
            return 'SteamID tidak valid.';
        }

        // Cari user berdasarkan SteamID.
        $user = User::where('steam_id', $steamId)->first();

        // Kalau belum ada, buat user baru.
        if (!$user) {
            $user = User::create([
                'name' => 'Steam User',
                'steam_id' => $steamId,
            ]);
        }

        // Login user ke Laravel.
        Auth::login($user);

        // Regenerasi session untuk keamanan.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
