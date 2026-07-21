<?php

namespace App\Services;

use App\Models\CaptainWallet;

class CaptainRankingService
{
    public function top()
    {
        return CaptainWallet::with([
            'captain.user'
        ])
            ->where('weekly_relics', '>', 0)
            ->orderByDesc('weekly_relics')
            ->orderByDesc('coins')
            ->limit(10)
            ->get();
    }
}
