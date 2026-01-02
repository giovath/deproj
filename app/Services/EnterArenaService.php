<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\User;

class EnterArenaService
{
    public function handle(User $user): GameMatch
    {
        // 0️⃣ Já está em um match em andamento?
        $existingMatch = GameMatch::whereIn('status', ['waiting', 'ready'])
            ->where(function ($q) use ($user) {
                $q->where('slot_1_user_id', $user->id)
                    ->orWhere('slot_2_user_id', $user->id);
            })
            ->first();

        if ($existingMatch) {
            return $existingMatch;
        }

        // 1️⃣ Existe match aguardando com slot livre?
        $match = GameMatch::where('status', 'waiting')
            ->where(function ($q) {
                $q->whereNull('slot_1_user_id')
                    ->orWhereNull('slot_2_user_id');
            })
            ->first();

        // 2️⃣ Se não existe -> cria um novo
        if (!$match) {
            $match = GameMatch::create([
                'status' => 'waiting',
            ]);
        }

        // 3️⃣ Ocupa slot seguro
        $match->occupySlot($user->id);

        // 4️⃣ Se ficou completo → mudar status
        if ($match->slot_1_user_id && $match->slot_2_user_id) {
            $match->status = 'ready';
            $match->save();
        }

        return $match;
    }
}
