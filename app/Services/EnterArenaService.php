<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\User;

class EnterArenaService
{
    public function handle(User $user): GameMatch
    {

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
            ->first();

        if ($existingMatch) {
            return $existingMatch;
        }

        $match = GameMatch::where('status', 'waiting')
            ->whereNotNull('slot_1_user_id')
            ->whereNull('slot_2_user_id')
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();


        // 2️⃣ Se não existe, criar um novo vazio
        if (!$match) {
            $match = GameMatch::create([
                'status' => 'waiting',
            ]);
        }

        // 3️⃣ Ocupa slot com regra segura (slot1 sempre primeiro)
        $match->occupySlot($user->id);

        return $match;
    }
}
