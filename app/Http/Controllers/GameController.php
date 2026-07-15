<?php

namespace App\Http\Controllers;

use App\Services\Games\GameCatalogService;

class GameController extends Controller
{
    public function index(GameCatalogService $catalog)
    {
        $games = $catalog->games();

        return view('jogos', compact('games'));
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
