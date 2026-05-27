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
Route::get('/invite/{match}', function (GameMatch $match) {

    session(['invited_match_id' => $match->id]);

    return redirect()->route('home');
})->name('arena.invite.public');


Route::middleware('auth')->post('/arena/start/{match}', [ArenaController::class, 'start'])
    ->name('arena.start');

Route::middleware('auth')->post('/arena/ready/{match}', [ArenaController::class, 'ready'])
    ->name('arena.ready');

Route::middleware('auth')->post(
    '/arena/choose-game/{match}',
    [ArenaController::class, 'chooseGame']
)->name('arena.choose-game');

Route::middleware('auth')->get(
    '/arena/games',
    [ArenaController::class, 'games']
)->name('arena.games');

Route::middleware('auth')->post(
    '/arena/leave/{match}',
    [ArenaController::class, 'leave']
)->name('arena.leave');

Route::get('/oportunidades', function () {
    return view('oportunidades');
})->name('oportunidades');

Route::get('/gol-de-premios', function () {
    return view('gol-de-premios');
})->name('gol-de-premios');

Route::get('/credito', function () {
    return view('credito');
})->name('credito');

Route::get('/renda', function () {
    return view('renda');
})->name('renda');


Route::get('/campanha', function () {
    return view('campanha');
})->name('campanha');

Route::get('/ilha-da-fortuna', function () {
    return view('ilha-da-fortuna');
})->name('ilha-da-fortuna');

Route::get('/navio', function () {
    return view('navio');
})->name('navio');

/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
