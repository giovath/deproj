<?php

namespace App\Services;

use App\Models\CaptainWallet;

class CaptainRankingService
{
    public function top()
    {
        return CaptainWallet::with('captain')
            ->where('relics', '>', 0)
            ->orderByDesc('relics')
            ->orderByDesc('coins')
            ->limit(10)
            ->get();
    }
}
