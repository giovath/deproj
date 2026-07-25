<?php

namespace App\Http\Controllers;

use App\Services\Games\GameCatalogService;
use App\Services\CaptainService;
use App\Services\CaptainStateService;

class GameController extends Controller
{
    public function index(
        GameCatalogService $catalog,
        CaptainService $captainService,
        CaptainStateService $stateService
    ) {

        $games = $catalog->games();

        $captain = $captainService->current();

        $wallet = null;

        if ($captain) {
            $wallet = $stateService->wallet($captain);
        }

        return view('jogos', compact(
            'games',
            'wallet'
        ));
    }


    public function play(
        $game,
        GameCatalogService $catalog
    ) {

        $selectedGame = $catalog
            ->games()
            ->firstWhere('id', $game);

        if (!$selectedGame) {

            return redirect('/jogos')
                ->with('error', 'Jogo não encontrado.');
        }

        return view('jogo-casual', [

            'gameTitle' => $selectedGame->title,

            'gameUrl' => $selectedGame->playUrl,

        ]);
    }
}
