<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TikTokAuthController;
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
use App\Services\TreasureProgressService;


use App\Http\Controllers\ExpeditionController;

Route::get('/', function (
    CaptainService $captainService,
    TreasureProgressService $progressService
) {


    $captain = $captainService->getOrCreate();


    $progress =
        $progressService->getOrCreate(
            $captain
        );


    return view('welcome', [

        'mission1Completed' =>
        $progress->mission1_completed,


        'mission2Completed' =>
        $progress->mission2_completed,


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

Route::get('/navio', function (
    CaptainService $captainService,
    TreasureProgressService $progressService
) {


    $captain =
        $captainService->getOrCreate();


    $progress =
        $progressService->getOrCreate(
            $captain
        );


    if (!$progress->mission1_completed) {

        return redirect('/');
    }


    return view('navio', [

        'captain' => $captain,

        'referralCompleted' =>
        $captain->referral_completed

    ]);
})->name('navio');

Route::post('/mission1/complete', function (
    CaptainService $captainService,
    TreasureProgressService $progressService
) {


    $captain =
        $captainService->getOrCreate();


    $progressService->completeMission1(
        $captain
    );


    if (session()->has('referred_by')) {


        $referrer =
            Captain::find(
                session('referred_by')
            );


        if ($referrer) {

            $referrer->update([
                'referral_completed' => true
            ]);
        }


        session()->forget('referred_by');
    }


    return response()->json([
        'success' => true
    ]);
});

Route::post('/mission2/complete', function (
    CaptainService $captainService,
    TreasureProgressService $progressService
) {


    $captain =
        $captainService->getOrCreate();


    $progressService->completeMission2(
        $captain
    );


    return response()->json([
        'success' => true
    ]);
});

Route::get('/tesouro', function (
    CaptainService $captainService,
    TreasureProgressService $progressService
) {


    $captain =
        $captainService->getOrCreate();


    $progress =
        $progressService->getOrCreate(
            $captain
        );


    // Usuário logado: verifica o tempo do baú diário
    if (Auth::check()) {


        $nextTreasure =
            Auth::user()->next_treasure_at;


        if ($nextTreasure && now()->lt($nextTreasure)) {


            return redirect('/porto')
                ->with(
                    'message',
                    'Seu baú diário ainda não está disponível.'
                );
        }
    }


    // Precisa concluir as missões antes de abrir o baú

    if (!$progress->treasure_available) {


        return redirect('/');
    }


    return view('tesouro');
});

Route::post('/tesouro/coletar', function (
    CaptainService $captainService,
    CaptainStateService $stateService,
    TreasureProgressService $progressService
) {

    $user = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | Recupera capitão
    |--------------------------------------------------------------------------
    */

    $captain = $captainService->getOrCreate();



    /*
    |--------------------------------------------------------------------------
    | Verifica progresso das missões
    |--------------------------------------------------------------------------
    */

    $progress = $progressService->getOrCreate($captain);


    if (!$progress->treasure_available) {

        return response()->json([

            'success' => false,

            'message' => 'Tesouro ainda não liberado.'

        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | Controle diário para usuário autenticado
    |--------------------------------------------------------------------------
    */

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



    /*
    |--------------------------------------------------------------------------
    | Gera recompensa
    |--------------------------------------------------------------------------
    */

    $coins = random_int(100, 199);



    /*
    |--------------------------------------------------------------------------
    | Adiciona moedas na carteira
    |--------------------------------------------------------------------------
    */

    $stateService->addCoins(

        $captain,

        $coins

    );



    /*
    |--------------------------------------------------------------------------
    | Define próximo baú do usuário autenticado
    |--------------------------------------------------------------------------
    */

    if ($user) {


        $user->next_treasure_at =
            now()->addDay();


        $user->save();
    }



    /*
    |--------------------------------------------------------------------------
    | Finaliza progresso do tesouro
    |--------------------------------------------------------------------------
    */

    $progress->update([

        'treasure_available' => false,

        'treasure_collected' => true,

    ]);



    /*
    |--------------------------------------------------------------------------
    | Sessões auxiliares (opcional)
    |--------------------------------------------------------------------------
    */

    session([

        'coins' =>
        session('coins', 0) + $coins,

        'treasure_collected' => true,

    ]);



    return response()->json([

        'success' => true,

        'coins' => $coins

    ]);
});

Route::get('/porto', function (
    CaptainService $captainService,
    CaptainStateService $stateService,
    CaptainRankingService $rankingService
) {

    $coins = 0;
    $participations = 0;
    $relics = 0;


    $captain = $captainService->current();


    if ($captain) {

        $wallet = $stateService->wallet($captain);


        $coins = $wallet->coins;

        $participations = $wallet->participations;

        $relics = $wallet->relics;
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
