<?php

namespace App\Services;

use App\Models\Captain;
use App\Models\DailyTreasureProgress;

class TreasureProgressService
{


    public function getOrCreate(
        Captain $captain
    ) {

        return DailyTreasureProgress::firstOrCreate(

            [
                'captain_id' => $captain->id
            ],

            [

                'mission1_completed' => false,

                'mission2_completed' => false,

                'treasure_available' => false,

                'treasure_collected' => false,

            ]

        );
    }



    public function completeMission1(
        Captain $captain
    ) {

        $progress =
            $this->getOrCreate($captain);


        $progress->update([

            'mission1_completed' => true

        ]);


        return $progress;
    }




    public function completeMission2(
        Captain $captain
    ) {

        $progress =
            $this->getOrCreate($captain);


        $progress->update([

            'mission2_completed' => true,

            'treasure_available' => true,

        ]);


        return $progress;
    }
}
