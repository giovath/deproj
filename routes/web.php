<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;

Route::get('/', function () {
    return view('welcome', [
        'match' => null,
    ]);
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


/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
