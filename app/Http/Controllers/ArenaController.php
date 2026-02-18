<?php

namespace App\Http\Controllers;

use App\Services\EnterArenaService;
use Illuminate\Support\Facades\Auth;
use App\Services\GamezopService;
use Illuminate\Support\Facades\DB;


use App\Models\GameMatch;


class ArenaController extends Controller
{

    private const GAMEZOP_GAME_CODE = 'hgempP8Sc';

    public function enter(EnterArenaService $service)
    {

        // 🧲 PRIORIDADE: convite
        if (session()->has('invited_match_id')) {

            $matchId = session()->pull('invited_match_id');

            $match = DB::transaction(function () use ($matchId) {

                $match = GameMatch::lockForUpdate()->find($matchId);

                if ($match && $match->hasFreeSlot()) {
                    $match->occupySlot(Auth::id());
                    $match->markReady(Auth::id());
                    return $match;
                }

                return null;
            });

            if ($match) {
                session(['match_id' => $match->id]);

                return response()->json([
                    'match_id' => $match->id,
                    'status'   => $match->status,
                ]);
            }
        }

        // 🔁 fluxo normal (SEM convite)
        if (session()->has('match_id')) {
            $match = GameMatch::find(session('match_id'));

            if (
                $match &&
                in_array(Auth::id(), [
                    $match->slot_1_user_id,
                    $match->slot_2_user_id
                ])
            ) {
                return response()->json([
                    'match_id' => $match->id,
                    'status'   => $match->status,
                ]);
            }

            session()->forget('match_id');
        }

        // 🔨 criação padrão
        $match = $service->handle(Auth::user());
        $match->markReady(Auth::id());

        session(['match_id' => $match->id]);

        return response()->json([
            'match_id' => $match->id,
            'status'   => $match->status,
        ]);
    }


    public function status(GameMatch $match)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => $match->status,
            ]);
        }

        $userId = Auth::id();

        if (
            $match->slot_1_user_id === $userId ||
            $match->slot_2_user_id === $userId
        ) {
            $opponent =
                $match->slot_1_user_id === $userId
                ? $match->slot2User
                : $match->slot1User;

            return response()->json([
                'status' => $match->status,
                'me' => [
                    'id' => $userId,
                    'slot' => $match->slot_1_user_id === $userId ? 1 : 2,
                ],
                'players' => [
                    1 => $match->slot1User ? [
                        'id' => $match->slot1User->id,
                        'name' => $match->slot1User->name,
                        'avatar' => $match->slot1User->avatar_url,
                    ] : null,
                    2 => $match->slot2User ? [
                        'id' => $match->slot2User->id,
                        'name' => $match->slot2User->name,
                        'avatar' => $match->slot2User->avatar_url,
                    ] : null,
                ],
            ]);
        }

        return response()->json([
            'status' => $match->status,
        ]);
    }



    public function joinInvite(GameMatch $match)
    {
        abort_if(in_array($match->status, ['playing', 'finished']), 403);

        session([
            'invited_match_id' => $match->id,
        ]);

        // NÃO ocupa slot
        // NÃO marca ready
        // NÃO mexe em status

        return redirect()->route('home');
    }




    public function start(GameMatch $match)
    {
        abort_unless(
            in_array(Auth::id(), [$match->slot_1_user_id, $match->slot_2_user_id]),
            403
        );

        if (!$match->bothReady()) {
            return response()->json([
                'message' => 'Aguardando o outro jogador'
            ], 409);
        }

        if ($match->status !== 'playing') {
            $match->update(['status' => 'playing']);
        }

        session(['match_id' => $match->id]);

        return response()->json([
            'redirect' => route('arena.play', $match)
        ]);
    }


    public function ready(GameMatch $match)
    {
        abort_unless(
            in_array(Auth::id(), [
                $match->slot_1_user_id,
                $match->slot_2_user_id
            ]),
            403
        );

        abort_if($match->status === 'playing', 403);

        $match->markReady(Auth::id());

        return response()->json([
            'status' => $match->status,
            'both_ready' => $match->bothReady(),
        ]);
    }



    public function play($matchId, GamezopService $gamezop)
    {
        $game = $gamezop->getGameByCode(self::GAMEZOP_GAME_CODE);

        abort_if(!$game, 404, 'Jogo não disponível');

        $roomDetails = [
            'roomId' => 'match_' . $matchId,

            'user' => [
                'name'  => Auth::user()->name,
                'sub'   => (string) Auth::id(),
                'photo' => Auth::user()->avatar_url ?: '',
            ],

            'minPlayers' => 2,
            'maxPlayers' => 2,
            'maxWait' => 120,

            // Pool só aceita 1 round
            'rounds' => 1,

            'text' => 'go_home',
            'allowBots' => false,
        ];

        $encoded = rtrim(
            base64_encode(json_encode($roomDetails)),
            '='
        );

        $url = $game['url'] . '?roomDetails=' . urlencode($encoded);

        return redirect()->away($url);
    }
}
