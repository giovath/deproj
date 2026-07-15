<?php

namespace App\Services;

use App\Models\Captain;
use App\Models\CaptainWallet;

class CaptainStateService
{

    public function wallet(Captain $captain)
    {
        return CaptainWallet::firstOrCreate(
            [
                'captain_id' => $captain->id
            ],
            [
                'coins' => 0,
                'participations' => 0,
                'relics' => 0
            ]
        );
    }


    public function addCoins(
        Captain $captain,
        int $amount
    ) {

        $wallet = $this->wallet($captain);

        $wallet->increment(
            'coins',
            $amount
        );

        return $wallet;
    }

    public function addParticipation(
        Captain $captain,
        int $amount = 1
    ) {
        $wallet = $this->wallet($captain);

        $wallet->increment(
            'participations',
            $amount
        );

        return $wallet;
    }


    public function addRelics(
        Captain $captain,
        int $amount
    ) {
        $wallet = $this->wallet($captain);

        $wallet->increment(
            'relics',
            $amount
        );

        return $wallet;
    }
}
