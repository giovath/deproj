<?php

namespace App\Http\Controllers;

use App\Services\EnterArenaService;
use Illuminate\Support\Facades\Auth;

class ArenaController extends Controller
{
    public function enter(EnterArenaService $service)
    {
        $match = $service->handle(Auth::user());

        return response()->json([
            'match_id' => $match->id,
            'status'   => $match->status,
            'role'     => $match->player1_id === Auth::id()
                ? 'player1'
                : 'player2',
        ]);
    }
}
