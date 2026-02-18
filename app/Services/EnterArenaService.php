<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnterArenaService
{
    public function handle(User $user): GameMatch
    {
        return DB::transaction(function () use ($user) {

            // 🧹 Limpa matches zumbis do usuário
            GameMatch::whereIn('status', ['waiting', 'ready'])
                ->where(function ($q) use ($user) {
                    $q->where('slot_1_user_id', $user->id)
                        ->orWhere('slot_2_user_id', $user->id);
                })
                ->where('updated_at', '<', now()->subMinutes(3))
                ->delete();

            // 0️⃣ Já está em um match ativo?
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

            // 1️⃣ Procura match disponível
            $match = GameMatch::where('status', 'waiting')
                ->whereNotNull('slot_1_user_id')
                ->whereNull('slot_2_user_id')
                ->where('updated_at', '>=', now()->subMinutes(2))
                ->lockForUpdate()
                ->first();

            // 2️⃣ Se não existe, cria
            if (!$match) {
                $match = GameMatch::create([
                    'status' => 'waiting',
                ]);
            }

            // 🚨 GARANTIA ABSOLUTA
            if (!$match) {
                throw new \Exception('Falha ao criar ou recuperar GameMatch');
            }

            // 3️⃣ Ocupa slot com segurança
            $match->occupySlot($user->id);
            $match->markReady($user->id);

            return $match->fresh();
        });
    }
}
