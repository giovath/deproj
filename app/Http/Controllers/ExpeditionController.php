<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data\Game;

class ExpeditionController extends Controller
{
    public function start(Request $request, $game)
    {
        $participations = session('participations', 0);

        if ($participations <= 0) {

            return redirect('/porto')
                ->with('error', 'Você não possui participações disponíveis.');
        }


        session([
            'participations' => $participations - 1,

            'expedition_active' => true,

            'expedition_game' => $game,

            'expedition_started_at' => now(),
        ]);


        return redirect('/expedicao/jogar');
    }


    public function play()
    {
        if (!session('expedition_active')) {

            return redirect('/porto');
        }


        return view('expedicao-jogar');
    }
}
