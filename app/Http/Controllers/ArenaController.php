<?php

namespace App\Http\Controllers;

use App\Services\EnterArenaService;
use Illuminate\Support\Facades\Auth;
use App\Services\GamezopService;


use App\Models\GameMatch;


class ArenaController extends Controller
{

    private const GAMEZOP_GAME_CODE = 'hgempP8Sc';

    public function enter(EnterArenaService $service)
    {
        if (session()->has('match_id')) {
            $match = GameMatch::find(session('match_id'));

            if ($match) {

                // 👇 ISSO AQUI
                if ($match->status === 'playing') {
                    return response()->json([
                        'match_id' => $match->id,
                        'status'   => $match->status,
                    ]);
                }

                if (
                    in_array(Auth::id(), [
                        $match->slot_1_user_id,
                        $match->slot_2_user_id
                    ]) &&
                    in_array($match->status, ['waiting', 'ready'])
                ) {
                    return response()->json([
                        'match_id' => $match->id,
                        'status'   => $match->status,
                    ]);
                }
            }

            // 🚫 Só limpa sessão se NÃO estiver playing
            session()->forget('match_id');
        }

        $match = $service->handle(Auth::user());

        // 👇 ESSENCIAL (estava faltando)
        $match->markReady(Auth::id());

        if ($match->bothReady()) {
            $match->update(['status' => 'ready']);
        }

        session(['match_id' => $match->id]);

        return response()->json([
            'match_id' => $match->id,
            'status'   => $match->status,
        ]);
    }


    public function status(GameMatch $match)
    {
        // Caso não esteja logado, não mostra nada sensível
        if (!Auth::check()) {
            return response()->json([
                'status' => $match->status,
            ]);
        }

        $userId = Auth::id();

        // Se é jogador do match → status completo
        if (
            $match->slot_1_user_id === $userId ||
            $match->slot_2_user_id === $userId
        ) {
            return response()->json([
                'status' => $match->status,
                'opponent' => [
                    'name' => $match->slot_1_user_id === $userId
                        ? optional($match->slot2User)->name
                        : optional($match->slot1User)->name,
                    'avatar' => $match->slot_1_user_id === $userId
                        ? optional($match->slot2User)->avatar_url
                        : optional($match->slot1User)->avatar_url,
                ],
            ]);
        }

        // Usuário logado, mas ainda não entrou no match (convite)
        return response()->json([
            'status' => $match->status,
        ]);
    }


    public function joinInvite(GameMatch $match)
    {
        $userId = Auth::id();

        // 1️⃣ Match não pode estar em jogo ou finalizado
        abort_if(in_array($match->status, ['playing', 'finished']), 403);

        // 2️⃣ Não permitir o mesmo usuário duas vezes
        abort_if(
            $match->slot_1_user_id === $userId ||
                $match->slot_2_user_id === $userId,
            403
        );

        // 3️⃣ Se slot1 não existe → match inválido
        if (is_null($match->slot_1_user_id)) {
            $match->delete();

            return redirect()
                ->route('home')
                ->with('error', 'O convite expirou ou o criador saiu.');
        }

        // 4️⃣ Ocupa o slot livre
        $match->occupySlot($userId);

        // 5️⃣ Marca ESTE jogador como pronto
        $match->markReady($userId);

        // 6️⃣ Se os dois estão prontos, muda status para ready
        if ($match->bothReady()) {
            $match->update(['status' => 'ready']);
        }

        // 7️⃣ Salva match na sessão
        session(['match_id' => $match->id]);

        // 8️⃣ Volta pra home (UI já vai reagir via polling)
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
