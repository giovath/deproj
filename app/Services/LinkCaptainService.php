<?php

namespace App\Services;

use App\Models\User;
use App\Models\Captain;
use App\Services\TreasureProgressService;

class LinkCaptainService
{
    public function execute(
        User $user,
        TreasureProgressService $progressService
    ) {

        if (!session()->has('captain_id')) {
            return;
        }


        $sessionCaptain = Captain::find(
            session('captain_id')
        );


        if (!$sessionCaptain) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | O usuário já possui um capitão?
        |--------------------------------------------------------------------------
        */

        $existingCaptain = Captain::where(
            'user_id',
            $user->id
        )->first();


        if ($existingCaptain) {


            /*
            |--------------------------------------------------------------------------
            | Restaura o capitão oficial
            |--------------------------------------------------------------------------
            */

            session([
                'captain_id' => $existingCaptain->id
            ]);



            /*
            |--------------------------------------------------------------------------
            | Remove capitão anônimo temporário
            |--------------------------------------------------------------------------
            */

            if ($sessionCaptain->id !== $existingCaptain->id) {

                $sessionCaptain->delete();
            }


            return;
        }



        /*
        |--------------------------------------------------------------------------
        | Primeiro login: vincula capitão anônimo
        |--------------------------------------------------------------------------
        */

        $sessionCaptain->update([

            'user_id' => $user->id

        ]);



        /*
        |--------------------------------------------------------------------------
        | Bônus do primeiro login
        |--------------------------------------------------------------------------
        */

        if (!$user->first_treasure_bonus_claimed) {


            $user->update([

                'next_treasure_at' => now(),

                'first_treasure_bonus_claimed' => true,

            ]);



            $progress =
                $progressService->getOrCreate(
                    $sessionCaptain
                );



            $progress->update([

                'mission1_completed' => false,

                'mission2_completed' => false,

                'treasure_available' => true,

                'treasure_collected' => false,

            ]);
        }



        session([

            'captain_id' => $sessionCaptain->id

        ]);
    }
}
