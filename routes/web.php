<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SteamAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SteamController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/auth/steam', [SteamAuthController::class, 'redirectToSteam'])
    ->name('steam.redirect');

Route::get('/auth/steam/callback', [SteamAuthController::class, 'handleCallback'])
    ->name('steam.callback');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('steam.redirect');
})->middleware('auth')->name('logout');

Route::post('/steam/import', [SteamController::class, 'import'])
    ->middleware('auth')
    ->name('steam.import');

Route::get('/steam/search', [SteamController::class, 'search'])
    ->middleware('auth')
    ->name('steam.search');

Route::get('/steam/games', [SteamController::class, 'searchPage'])
    ->middleware('auth')
    ->name('steam.games');

Route::post('/steam/games/{steamAppId}/add', [SteamController::class, 'addToLibrary'])
    ->middleware('auth')
    ->name('steam.add');

Route::get('/library', [LibraryController::class, 'index'])
    ->middleware('auth')
    ->name('library');

Route::get('/library/{userGame}', [GameController::class, 'show'])
    ->middleware('auth')
    ->name('library.show');

Route::get('/library/{userGame}/edit', [GameController::class, 'edit'])
    ->middleware('auth')
    ->name('library.edit');

Route::put('/library/{userGame}', [GameController::class, 'update'])
    ->middleware('auth')
    ->name('library.update');

Route::delete('/library/{userGame}', [GameController::class, 'destroy'])
    ->middleware('auth')
    ->name('library.destroy');

Route::get('/debug-url', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'environment' => app()->environment(),
        'url_root' => url('/'),
        'asset' => asset('build/assets/app.css'),
        'scheme' => request()->getScheme(),
        'is_secure' => request()->isSecure(),
    ]);
});
