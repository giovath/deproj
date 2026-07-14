<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Games\GameCatalogService;

class ExpeditionController extends Controller
{
    public function start(
        Request $request,
        $game,
        GameCatalogService $catalog
    ) {
        $participations = session('participations', 0);

        if ($participations <= 0) {

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

        session([
            'participations' => $participations - 1,

            'expedition_active' => true,

            'expedition_game_id' => $selectedGame->id,

            'expedition_game_title' => $selectedGame->title,

            'expedition_game_url' => $selectedGame->playUrl,

            'expedition_game_slug' => $selectedGame->slug,

            'expedition_started_at' => now(),

            'expedition_duration' => 20,
        ]);

        return redirect('/expedicao/jogar');
    }


    public function play()
    {
        if (!session('expedition_active')) {
            return redirect('/porto');
        }

        /*
        dd(
            session('expedition_duration'),
            session('expedition_started_at')
        );*/

        return view('expedicao-jogar', [
            'gameTitle' => session('expedition_game_title'),
            'gameUrl' => session('expedition_game_url'),
            'startedAt' => session('expedition_started_at'),
            'duration' => session('expedition_duration', 20),
        ]);
    }

    public function finish()
    {
        if (!session('expedition_active')) {

            return response()->json([
                'success' => false,
                'message' => 'Nenhuma expedição ativa.'
            ]);
        }


        $startedAt = \Carbon\Carbon::parse(
            session('expedition_started_at')
        );

        $duration = session('expedition_duration', 20);

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
     | Recompensa
     |--------------------------------------------------------------------------
    */


        $relics = random_int(1, 5);

        $currentRelics = session('expedition_relics', 0);

        session([
            'expedition_relics' => $currentRelics + $relics,
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
