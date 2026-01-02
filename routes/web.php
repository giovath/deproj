<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;
use App\Models\GameMatch;

Route::get('/', function () {
    $match = \App\Models\GameMatch::latest()->first();
    return view('welcome', compact('match'));
})->name('home');



Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');

/**
 * OAuth TikTok
 */
Route::get('/auth/tiktok/redirect', [TikTokAuthController::class, 'redirect'])
    ->name('auth.tiktok.redirect');

Route::get('/auth/tiktok/callback', [TikTokAuthController::class, 'callback'])
    ->name('auth.tiktok.callback');

Route::middleware('auth')->post('/arena/enter', [ArenaController::class, 'enter'])
    ->name('arena.enter');

Route::middleware('auth')->get('/arena/status/{match}', [ArenaController::class, 'status'])
    ->name('arena.status');

Route::middleware('auth')->get('/play/{match}', [ArenaController::class, 'play'])
    ->name('arena.play');

// convite público
Route::get('/invite/{match}', function (GameMatch $match) {
    session(['invited_match_id' => $match->id]);
    return redirect()->route('login');
})->name('arena.invite.public');

// consumir convite
Route::middleware('auth')->get('/arena/join/{match}', [ArenaController::class, 'joinInvite'])
    ->name('arena.join.invite');


/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
