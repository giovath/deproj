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

            $matchId = session('invited_match_id');

            $match = DB::transaction(function () use ($matchId) {

                $match = GameMatch::lockForUpdate()->find($matchId);

                if (
                    $match &&
                    $match->hasFreeSlot() &&
                    !in_array(Auth::id(), [
                        $match->slot_1_user_id,
                        $match->slot_2_user_id
                    ])
                ) {
                    $match->occupySlot(Auth::id());
                    return $match;
                }

                return null;
            });

            if ($match) {

                session(['match_id' => $match->id]);

                // remove o convite apenas depois de entrar
                session()->forget('invited_match_id');

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

            // 🔥 BUSCAR NOME DO JOGO
            $gameName = null;

            if ($match->game_code) {
                $games = collect(config('gamezop_games.sync_core'))
                    ->merge(config('gamezop_games.sync_casual'))
                    ->merge(config('gamezop_games.async_strategy'))
                    ->merge(config('gamezop_games.async_arcade'));

                $game = $games->firstWhere('code', $match->game_code);
                $gameName = $game['name'] ?? null;
            }

            return response()->json([
                'status'     => $match->status,
                'game_code'  => $match->game_code,
                'game_name'  => $gameName, // 👈 novo campo
                'me' => [
                    'id'   => $userId,
                    'slot' => $mySlot,
                ],
                'opponent' => $opponent ? [
                    'id'     => $opponent->id,
                    'name'   => $opponent->name,
                    'avatar' => $opponent->avatar,
                ] : null,
            ]);
        }

        return response()->json([
            'status' => $match->status,
        ]);
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

    public function chooseGame(Request $request, GameMatch $match)
    {
        abort_if(!$match->slot_1_user_id || !$match->slot_2_user_id, 409, 'Aguardando segundo jogador');
        abort_if($match->game_code, 409, 'Jogo já escolhido');

        $gameCode = $request->input('game_code');

        $curator = app(GamesCuratorService::class);

        abort_if(
            !$curator->isValidMultiplayer($gameCode),
            422,
            'Jogo inválido'
        );

        $match->game_code = $gameCode;
        $match->save();

        return response()->json([
            'success' => true,
            'game_code' => $gameCode
        ]);
    }


    public function games(GamesCuratorService $curator)
    {
        $codes = $curator->multiplayerOptions();

        $games = collect(config('gamezop_games'))
            ->flatten(1)
            ->whereIn('code', $codes)
            ->values();

        return response()->json($games);
    }

    public function leave(GameMatch $match)
    {
        $userId = Auth::id();

        if (!in_array($userId, [$match->slot_1_user_id, $match->slot_2_user_id])) {
            abort(403);
        }

        $match->removePlayer($userId);

        session()->forget('match_id');

        return response()->json([
            'success' => true
        ]);
    }
}
