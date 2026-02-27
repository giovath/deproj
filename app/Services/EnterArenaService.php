<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\GamesCuratorService;


class EnterArenaService
{
    public function handle(User $user): GameMatch
    {
        return DB::transaction(function () use ($user) {

            // 🧹 Limpa matches zumbis
            GameMatch::whereIn('status', ['waiting', 'ready'])
                ->where(function ($q) use ($user) {
                    $q->where('slot_1_user_id', $user->id)
                        ->orWhere('slot_2_user_id', $user->id);
                })
                ->where('updated_at', '<', now()->subMinutes(3))
                ->delete();

            // Já está em match ativo?
            $existingMatch = GameMatch::whereIn('status', ['waiting', 'ready'])
                ->where(function ($q) use ($user) {
                    $q->where('slot_1_user_id', $user->id)
                        ->orWhere('slot_2_user_id', $user->id);
                })
                ->lockForUpdate()
                ->first();

            if ($existingMatch) {
                return $existingMatch;
            }

            // Procura match aguardando segundo jogador
            $match = GameMatch::where('status', 'waiting')
                ->whereNotNull('slot_1_user_id')
                ->whereNull('slot_2_user_id')
                ->where('updated_at', '>=', now()->subMinutes(2))
                ->lockForUpdate()
                ->first();

            // Se não existir, cria novo
            if (!$match) {
                $match = GameMatch::create([
                    'status' => 'waiting',
                    'game_code' => null,
                ]);
            }

            // Ocupa slot
            $match->occupySlot($user->id);

            return $match->fresh();
        });
    }
}
