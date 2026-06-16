<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GamezopWebhookController extends Controller
{
    public function score(Request $request)
    {
        Log::info(
            'GAMEZOP SCORE RECEIVED',
            [
                'headers' => $request->headers->all(),
                'payload' => $request->all(),
            ]
        );

        return response()->json([
            'success' => true
        ], 200);
    }
}
