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

        session()->forget('match_id');

        $match = $service->handle(Auth::user());
        $userId = Auth::id();

        session(['match_id' => $match->id]);


        return response()->json([
            'match_id' => $match->id,
            'status'   => $match->status,
            'role'     => $match->slot_1_user_id === $userId
                ? 'player1'
                : 'player2',
            'opponent' => [
                // envia informações básicas para a interface exibir o "avatar" do outro
                'name'  => $match->slot_1_user_id === $userId
                    ? optional($match->slot2User)->name
                    : optional($match->slot1User)->name,
                'avatar' => $match->slot_1_user_id === $userId
                    ? optional($match->slot2User)->avatar_url
                    : optional($match->slot1User)->avatar_url,
            ],
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

        // Match precisa estar aguardando
        abort_if($match->status !== 'waiting', 403);

        // Não permitir o mesmo usuário duas vezes
        abort_if(
            $match->slot_1_user_id === $userId ||
                $match->slot_2_user_id === $userId,
            403
        );

        // Se slot1 não existe → match órfão
        if (is_null($match->slot_1_user_id)) {
            $match->delete();
            return redirect()->route('home')
                ->with('error', 'O convite expirou ou o criador saiu.');
        }

        // Ocupa slot2 (ou slot livre) usando regra central
        $match->occupySlot($userId);
        session(['match_id' => $match->id]);

        return redirect()->route('home');
    }

    public function start(GameMatch $match)
    {
        abort_unless($match->isReady(), 403);

        abort_unless(
            in_array(Auth::id(), [$match->slot_1_user_id, $match->slot_2_user_id]),
            403
        );

        $match->status = 'playing';
        $match->save();

        return redirect()->route('arena.play', $match);
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
