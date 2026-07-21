<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
use App\Http\Controllers\ArenaController;
use App\Models\GameMatch;
use App\Models\Captain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\GameController;
use App\Services\Games\GameCatalogService;

use Illuminate\Support\Facades\Log;

use App\Services\CaptainService;
use App\Services\CaptainWalletService;
use App\Services\CaptainStateService;
use App\Services\CaptainRankingService;

use App\Services\GamezopService;
use App\Http\Controllers\GamezopWebhookController;

use App\Http\Controllers\ExpeditionController;

Route::get('/', function (
    CaptainStateService $stateService
) {

    $treasureState = $stateService->treasureState();

    if (Auth::check() && $treasureState['available']) {

        session()->forget([
            'mission1_completed',
            'mission2_completed',
            'treasure_available',
            'treasure_collected',
        ]);
    }

    return view('welcome', [

        'mission1Completed' =>
        session('mission1_completed', false),

        'mission2Completed' =>
        session('mission2_completed', false),

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


Route::get('/ilha-da-fortuna', function (
    Request $request,
    CaptainService $captainService,
    CaptainWalletService $walletService
) {

    $captain = $captainService->getOrCreate();


    $wallet = $walletService->getOrCreate($captain);


    $ref = $request->query('ref');


    if ($ref) {

        $referrer = Captain::where(
            'ref_code',
            $ref
        )->first();


        if ($referrer && $referrer->id !== $captain->id) {

            session([
                'referred_by' => $referrer->id
            ]);
        }
    }


    return view('ilha-da-fortuna', compact('wallet'));
});

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

    // Usuário logado: verifica o tempo do baú diário
    if (Auth::check()) {

        $nextTreasure = Auth::user()->next_treasure_at;

        if ($nextTreasure && now()->lt($nextTreasure)) {

            return redirect('/porto')
                ->with('message', 'Seu baú diário ainda não está disponível.');
        }
    } else {

        // Visitante: usa apenas a sessão
        if (session('treasure_collected')) {

            return redirect('/porto');
        }
    }

    // Precisa concluir as missões antes de abrir o baú
    if (!session('treasure_available')) {

        return redirect('/');
    }

    return view('tesouro');
});

Route::post('/tesouro/coletar', function (
    CaptainService $captainService,
    CaptainStateService $stateService
) {

    $user = Auth::user();


    // Visitantes só podem coletar uma vez por sessão
    if (!$user && session('treasure_collected')) {

        return response()->json([
            'success' => false
        ]);
    }


    // Usuários logados respeitam o tempo salvo no banco
    if ($user) {

        if (
            $user->next_treasure_at &&
            now()->lt($user->next_treasure_at)
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Baú diário indisponível.'
            ]);
        }
    }


    $coins = random_int(100, 199);


    $captain = $captainService->getOrCreate();


    $stateService->addCoins(
        $captain,
        $coins
    );


    // Define próximo baú diário para usuários logados
    if ($user) {

        $user->next_treasure_at = now()->addDay();

        $user->save();
    }


    session([

        'coins' => session('coins', 0) + $coins,

        'treasure_collected' => true,

        'treasure_available' => false,

    ]);


    return response()->json([
        'success' => true,
        'coins' => $coins
    ]);
});

Route::get('/porto', function (
    CaptainStateService $stateService,
    CaptainRankingService $rankingService
) {

    $coins = session('coins', 0);
    $participations = session('participations', 0);
    $relics = session('expedition_relics', 0);


    if (session()->has('captain_id')) {

        $captain = Captain::find(
            session('captain_id')
        );


        if ($captain) {

            $wallet = $stateService->wallet($captain);

            $coins = $wallet->coins;
            $participations = $wallet->participations;
            $relics = $wallet->relics;
        }
    }


    return view('porto', [

        'coins' => $coins,

        'participations' => $participations,

        'relics' => $relics,

        'ranking' => $rankingService->top(),

        'treasureState' => $stateService->treasureState(),

    ]);
});

Route::post(
    '/jogos/comprar-participacao',
    function (
        CaptainService $captainService,
        CaptainStateService $stateService
    ) {


        $captain = $captainService->current();


        if (!$captain) {

            return response()->json([
                'success' => false,
                'message' => 'Capitão não encontrado'
            ]);
        }


        $wallet = $stateService->wallet(
            $captain
        );


        if ($wallet->coins < 100) {

            return response()->json([
                'success' => false,
                'message' => 'Moedas insuficientes'
            ]);
        }



        $wallet->decrement(
            'coins',
            100
        );


        $wallet->increment(
            'participations'
        );



        session([

            'coins' => $wallet->coins,

            'participations' => $wallet->participations

        ]);



        return response()->json([

            'success' => true,

            'coins' => $wallet->coins,

            'participations' => $wallet->participations

        ]);
    }
);

Route::get('/jogos', [GameController::class, 'index']);


Route::post(
    '/expedicao/iniciar/{game}',
    [ExpeditionController::class, 'start']
);


Route::get(
    '/expedicao/jogar',
    [ExpeditionController::class, 'play']
);

Route::post(
    '/expedicao/finalizar',
    [ExpeditionController::class, 'finish']
);

Route::get('/jogo/{game}', [GameController::class, 'play']);


/**
 * Healthcheck
 */
Route::get('/ping', fn() => response('ok', 200));
