<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Games\GameCatalogService;
use App\Services\CaptainService;
use App\Services\CaptainStateService;

class ExpeditionController extends Controller
{
    public function start(
        Request $request,
        $game,
        GameCatalogService $catalog,
        CaptainService $captainService,
        CaptainStateService $stateService
    ) {

        $captain = $captainService->current();


        if (!$captain) {

            return redirect('/porto')
                ->with('error', 'Capitão não encontrado.');
        }


        $wallet = $stateService->wallet($captain);



        if ($wallet->participations <= 0) {

            return redirect('/porto')
                ->with('error', 'Você não possui participações disponíveis.');
        }



        $selectedGame = $catalog
            ->games()
            ->firstWhere('id', $game);



        if (!$selectedGame) {

            return redirect('/jogos')
                ->with('error', 'Jogo não encontrado.');
        }



        /*
        |--------------------------------------------------------------------------
        | Consome participação
        |--------------------------------------------------------------------------
        */

        $wallet->decrement(
            'participations'
        );



        session([

            'participations' => $wallet->participations,


            'expedition_active' => true,


            'expedition_game_id' => $selectedGame->id,


            'expedition_game_title' => $selectedGame->title,


            'expedition_game_url' => $selectedGame->playUrl,


            'expedition_game_slug' => $selectedGame->slug,


            'expedition_started_at' => now(),


            'expedition_duration' => 300,

        ]);



        return redirect('/expedicao/jogar');
    }





    public function play()
    {
        if (!session('expedition_active')) {

            return redirect('/porto');
        }



        return view('expedicao-jogar', [

            'gameTitle' => session('expedition_game_title'),

            'gameUrl' => session('expedition_game_url'),

            'startedAt' => session('expedition_started_at'),

            'duration' => session('expedition_duration', 300),

        ]);
    }





    public function finish(
        CaptainService $captainService,
        CaptainStateService $stateService
    ) {

        if (!session('expedition_active')) {


            return response()->json([

                'success' => false,

                'message' => 'Nenhuma expedição ativa.'

            ]);
        }




        $startedAt = \Carbon\Carbon::parse(
            session('expedition_started_at')
        );


        $duration = session('expedition_duration', 300);


        $elapsed = now()->timestamp - $startedAt->timestamp;



        if ($elapsed < $duration) {


            return response()->json([

                'success' => false,

                'message' => 'A expedição ainda está em andamento.',

                'remaining' => $duration - $elapsed

            ]);
        }

            /*
            |--------------------------------------------------------------------------
            | Recompensa da expedição
            |--------------------------------------------------------------------------
            | 35% -> 1 relíquia
            | 45% -> 2 relíquias
            | 20% -> 3 relíquias
            |--------------------------------------------------------------------------
            */

        $roll = random_int(1, 100);

        if ($roll <= 35) {

            $relics = 1;
        } elseif ($roll <= 80) {

            $relics = 2;
        } else {

            $relics = 3;
        }



        $captain = $captainService->current();



        if (!$captain) {


            return response()->json([

                'success' => false,

                'message' => 'Capitão não encontrado.'

            ]);
        }




        $wallet = $stateService->wallet(
            $captain
        );



        $wallet->increment(
            'relics',
            $relics
        );

        $wallet->increment(
            'weekly_relics',
            $relics
        );




        session([

            'expedition_relics' => $wallet->relics,


            'expedition_finished' => true,


            'expedition_active' => false,

        ]);





        return response()->json([


            'success' => true,


            'relics' => $relics,


            'elapsed' => $elapsed,


            'duration' => $duration,


        ]);
    }
}
