<?php

namespace App\Services;

use App\Models\CaptainWallet;
use App\Models\WeeklyRanking;
use Illuminate\Support\Facades\DB;

class WeeklyRankingService
{

    public function close()
    {

        return DB::transaction(function () {


            $weekKey = now()->format('o-\WW');


            $ranking = CaptainWallet::with('captain')
                ->where('weekly_relics', '>', 0)
                ->orderByDesc('weekly_relics')
                ->limit(3)
                ->get();



            foreach ($ranking as $index => $wallet) {


                WeeklyRanking::create([

                    'captain_id' => $wallet->captain_id,

                    'week_key' => $weekKey,

                    'position' => $index + 1,

                    'relics' => $wallet->weekly_relics,

                    'reward' => match ($index) {

                        0 => 20,

                        1 => 10,

                        2 => 5,
                    },

                ]);
            }



            CaptainWallet::query()
                ->update([
                    'weekly_relics' => 0
                ]);


            return true;
        });
    }
}
