<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\User;

class EnterArenaService
{
    public function handle(User $user): GameMatch
    {
        // 1️⃣ Existe match aguardando?
        $match = GameMatch::where('status', 'waiting')
            ->where(function ($q) {
                $q->whereNull('slot_1_user_id')
                    ->orWhereNull('slot_2_user_id');
            })
            ->first();

        // 2️⃣ Se não existir, cria um novo
        if (!$match) {
            $match = GameMatch::create([
                'status' => 'waiting',
            ]);
        }

        // 3️⃣ Ocupa slot
        $match->occupySlot($user->id);

        return $match;
    }
}
