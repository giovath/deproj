<?php

namespace App\Services;

use App\Models\CaptainWallet;
use App\Models\Captain;

class CaptainWalletService
{

    public function getOrCreate(Captain $captain): CaptainWallet
    {
        return CaptainWallet::firstOrCreate(
            [
                'captain_id' => $captain->id
            ],
            [
                'coins' => 0,
                'participations' => 0,
                'relics' => 0,
            ]
        );
    }
}
