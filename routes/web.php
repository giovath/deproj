<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TikTokAuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');

Route::get('/auth/tiktok/redirect', [TikTokAuthController::class, 'redirect'])
    ->name('auth.tiktok.redirect');

Route::get('/auth/tiktok/callback', [TikTokAuthController::class, 'callback'])
    ->name('auth.tiktok.callback');

Route::get('/auth/tiktok/callback', [TikTokAuthController::class, 'callback'])
    ->name('auth.tiktok.callback');
