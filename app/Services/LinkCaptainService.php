<?php

namespace App\Services;

use App\Models\User;
use App\Models\Captain;

class LinkCaptainService
{
    public function execute(User $user)
    {
        if (!session()->has('captain_id')) {
            return;
        }

        $captain = Captain::find(
            session('captain_id')
        );

        if (!$captain) {
            return;
        }

        if (!$captain->user_id) {

            $captain->update([
                'user_id' => $user->id
            ]);


            if (!$user->first_treasure_bonus_claimed) {

                $user->update([
                    'next_treasure_at' => now(),
                    'first_treasure_bonus_claimed' => true,
                ]);
            }
        }
    }
}
