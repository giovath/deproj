<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Games\GameCatalogService;

class GameController extends Controller
{
    public function index(GameCatalogService $catalog)
    {
        $games = $catalog->games();

        return view('jogos', compact('games'));
    }
}
