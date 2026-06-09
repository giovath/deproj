<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;
use App\Models\GameMatch;
use App\Models\Captain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

Route::get('/', function () {

    return view('welcome', [

        'mission1Completed' =>
        session('mission1_completed', false),

        'mission2Completed' =>
        session('mission2_completed', false)

    ]);
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


Route::get('/ilha-da-fortuna', function (Request $request) {

    $ref = $request->query('ref');

    if ($ref) {

        $captain = Captain::where(
            'ref_code',
            $ref
        )->first();

        if ($captain) {

            if (
                session()->has('captain_id')
                &&
                session('captain_id') == $captain->id
            ) {

                return view('ilha-da-fortuna');
            }

            session([
                'referred_by' => $captain->id
            ]);
        }
    }

    return view('ilha-da-fortuna');
})->name('ilha-da-fortuna');

Route::get('/navio', function () {

    if (!session('mission1_completed')) {

        return redirect('/');
    }

    $captain = null;

    if (session()->has('captain_id')) {

        $captain =
            Captain::find(
                session('captain_id')
            );
    }

    if (!$captain) {

        $captain = Captain::create([

            'ref_code' => Str::random(8),

        ]);

        session([
            'captain_id' => $captain->id
        ]);
    }

    return view('navio', [

        'captain' => $captain,

        'referralCompleted' =>
        $captain->referral_completed

    ]);
})->name('navio');

Route::post('/mission1/complete', function (Request $request) {

    session([
        'mission1_completed' => true,
        'mission1_completed_at' => now()->timestamp
    ]);

    if (session()->has('referred_by')) {

        $captain =
            Captain::find(
                session('referred_by')
            );

        if ($captain) {

            $captain->update([
                'referral_completed' => true
            ]);
        }

        session()->forget('referred_by');
    }

    return response()->json([
        'success' => true
    ]);
});
Route::post('/mission2/complete', function () {

    session([

        'mission2_completed' => true,
        'mission2_completed_at' => now()->timestamp,

        'treasure_available' => true

    ]);

    return response()->json([
        'success' => true
    ]);
});

Route::get('/tesouro', function () {
    return view('tesouro');
})->name('tesouro');

/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
