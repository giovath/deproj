<?php

namespace App\Http\Controllers;

use App\Services\EnterArenaService;
use Illuminate\Support\Facades\Auth;
use App\Services\GamezopService;
use Illuminate\Support\Facades\DB;
use App\Services\GamesCuratorService;
use Illuminate\Http\Request;



use App\Models\GameMatch;


class ArenaController extends Controller
{

    public function enter(
        EnterArenaService $service,
        GamesCuratorService $curator
    ) {

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
        $match = $service->handle(Auth::user(), $curator);

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

            $mySlot = $match->slot_1_user_id === $userId ? 1 : 2;


            return response()->json([
                'status' => $match->status,
                'game_code' => $match->game_code,
                'me' => [
                    'id' => $userId,
                    'slot' => $mySlot,
                ],
                'opponent' => $opponent ? [
                    'id' => $opponent->id,
                    'name' => $opponent->name,
                    'avatar' => $opponent->avatar,
                ] : null,
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

        if (!$match->game_code) {
            return response()->json([
                'message' => 'Escolha um jogo antes de iniciar'
            ], 409);
        }

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

    public function play(GameMatch $match, GamezopService $gamezop)
    {
        abort_unless(
            in_array(Auth::id(), [$match->slot_1_user_id, $match->slot_2_user_id]),
            403
        );

        abort_if(!$match->game_code, 409, 'Jogo não definido');

        $game = $gamezop->getGameByCode($match->game_code);

        abort_if(!$game, 404, 'Jogo não disponível');

        $roomDetails = [
            'roomId' => 'match_' . $match->id,

            'user' => [
                'name'  => Auth::user()->name,
                'sub'   => (string) Auth::id(),
                'photo' => Auth::user()->avatar_url ?: '',
            ],

            'minPlayers' => 2,
            'maxPlayers' => 2,
            'maxWait'    => 120,
            'rounds'     => 1,

            'text'       => 'go_home',
            'allowBots'  => false,
        ];

        $encoded = rtrim(
            base64_encode(json_encode($roomDetails)),
            '='
        );

        return redirect()->away(
            $game['url'] . '?roomDetails=' . urlencode($encoded)
        );
    }

    public function chooseGame(
        GameMatch $match,
        Request $request,
        GamesCuratorService $curator
    ) {
        abort_unless(
            in_array(Auth::id(), [$match->slot_1_user_id, $match->slot_2_user_id]),
            403
        );

        abort_if($match->status === 'playing', 403);

        $request->validate([
            'game_code' => 'required|string'
        ]);

        abort_unless(
            $curator->isValidMultiplayer($request->game_code),
            422
        );

        // evita sobrescrita
        if ($match->game_code) {
            return response()->json([
                'game_code' => $match->game_code
            ]);
        }

        $match->update([
            'game_code' => $request->game_code
        ]);

        return response()->json([
            'game_code' => $match->game_code
        ]);
    }


    public function games()
    {
        $matchId = session('match_id');

        if (!$matchId) {
            return response()->json([]);
        }

        $match = GameMatch::find($matchId);

        if (!$match) {
            return response()->json([]);
        }

        // ❌ Ainda não tem os dois jogadores
        if (!$match->slot_1_user_id || !$match->slot_2_user_id) {
            return response()->json([]);
        }

        // ❌ Jogo já foi escolhido
        if ($match->game_code) {
            return response()->json([]);
        }

        // ✅ Tudo certo → libera lista
        return response()->json(
            app(GamesCuratorService::class)->availableGames()
        );
    }
}
