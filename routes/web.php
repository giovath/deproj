<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;
use App\Models\GameMatch;

Route::get('/', function () {
    $match = \App\Models\GameMatch::latest()->first();
    return view('welcome', compact('match'));
})->name('home');


Route::get('/recompensas', function () {
    return view('recompensas');
})->name('recompensas');


Route::get('/sorteios', function () {
    return view('sorteios');
})->name('sorteios');

Route::get('/jogos', function () {
    return view('jogos');
})->name('jogos');

Route::get('/como-funciona', function () {
    return view('como-funciona');
})->name('como-funciona');

Route::get('/data-deletion', function () {
    return view('data-deletion');
})->name('data-deletion');

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
Route::get('/invite/{id}', function ($id) {
    $match = GameMatch::find($id);
    if (!$match) {
        return redirect()->route('home')
            ->with('error', 'O convite expirou ou o criador saiu.')
            ->with('open_invite_modal', true);
    }
    session(['invited_match_id' => $match->id]);
    return redirect()->route('home')->with('open_invite_modal', true);
})->name('arena.invite.public');

// consumir convite
Route::middleware('auth')->get('/arena/join/{id}', [ArenaController::class, 'joinInvite'])
    ->name('arena.join.invite');

Route::middleware('auth')->post('/arena/start/{match}', [ArenaController::class, 'start'])
    ->name('arena.start');



/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
