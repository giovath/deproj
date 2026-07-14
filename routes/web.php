<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;
use App\Models\GameMatch;
use App\Models\Captain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use App\Services\GamezopService;
use App\Http\Controllers\GamezopWebhookController;

Route::get('/', function () {

    return view('welcome', [

        'mission1Completed' =>
        session('mission1_completed', false),

        'mission2Completed' =>
        session('mission2_completed', false)

    ]);
})->name('home');



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

    if (!session('treasure_available')) {
        return redirect('/');
    }

    if (session('treasure_collected')) {
        return redirect('/porto');
    }

    return view('tesouro');
});

Route::get('/porto', function () {

    if (!session('treasure_collected')) {
        return redirect('/');
    }

    if (!session()->has('player_uuid')) {

        session([
            'player_uuid' => (string) Str::uuid()
        ]);
    }

    return view('porto', [
        'coins' => session('coins', 0),
        'entries' => session('entries', 0),
    ]);
});

Route::post('/tesouro/coletar', function () {

    if (session('treasure_collected')) {

        return response()->json([
            'success' => false
        ]);
    }

    session([
        'coins' => session('coins', 0) + 184,
        'treasure_collected' => true,
        'treasure_available' => false,
    ]);

    return response()->json([
        'success' => true
    ]);
});

Route::post('/porto/comprar-participacao', function () {

    $coins = session('coins', 0);

    if ($coins < 100) {

        return response()->json([
            'success' => false,
            'message' => 'Moedas insuficientes'
        ]);
    }

    session([
        'coins' => $coins - 100,
        'entries' => session('entries', 0) + 1,
    ]);

    return response()->json([
        'success' => true,
        'coins' => session('coins'),
        'entries' => session('entries'),
    ]);
});

Route::post('/porto/usar-participacao', function () {

    $entries = session('entries', 0);

    if ($entries <= 0) {

        return response()->json([
            'success' => false,
            'message' => 'Nenhuma participação disponível'
        ]);
    }

    session([
        'entries' => $entries - 1,
    ]);

    return response()->json([
        'success' => true,
        'entries' => session('entries'),
    ]);
});

Route::get('/jogo', function (
    GamezopService $gamezop
) {

    $game = $gamezop->getGameByCode(
        config('gamezop_games.featured_game.code')
    );

    abort_if(!$game, 404);

    $gameUrl =
        $game['url']
        . '&sub='
        . session('player_uuid');

    return view('jogo', [
        'gameUrl' => $gameUrl
    ]);
});

Route::post(
    '/webhooks/gamezop/score',
    [GamezopWebhookController::class, 'score']
);

Route::any('/webhooks/gamezop/teste', function (Request $request) {

    Log::info('WEBHOOK TESTE', [
        'method' => $request->method(),
        'payload' => $request->all(),
    ]);

    return response()->json([
        'success' => true,
        'method' => $request->method(),
    ]);
});

Route::get('/games-test', function () {

    $provider = app(App\Services\Games\Providers\GamePixProvider::class);

    return $provider->games();
});

/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
