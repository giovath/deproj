<?php

namespace App\Services;

use App\Models\Captain;
use App\Models\DailyTreasureProgress;

class TreasureProgressService
{
    /**
     * Retorna o progresso da jornada diária do Captain.
     *
     * Se ainda não existir, cria.
     *
     * Se existir, mas estiver expirado ou sem expires_at
     * (registros antigos), inicia uma nova jornada.
     */
    public function getOrCreate(
        Captain $captain
    ): DailyTreasureProgress {

        $progress = DailyTreasureProgress::firstOrCreate(

            [
                'captain_id' => $captain->id
            ],

            [
                'mission1_completed' => false,

                'mission2_completed' => false,

                'treasure_available' => false,

                'treasure_collected' => false,

                'expires_at' => now()->endOfDay(),
            ]

        );


        /*
        |--------------------------------------------------------------------------
        | Verifica se a jornada atual expirou
        |--------------------------------------------------------------------------
        |
        | Registros antigos podem estar com expires_at = null.
        | Nesse caso, tratamos como jornada expirada e começamos uma nova.
        |
        */

        if (
            !$progress->expires_at ||
            now()->greaterThan($progress->expires_at)
        ) {

            $progress->update([

                'mission1_completed' => false,

                'mission2_completed' => false,

                'treasure_available' => false,

                'treasure_collected' => false,

                'expires_at' => now()->endOfDay(),

            ]);

        }


        return $progress;
    }


    /**
     * Marca a missão 1 como concluída.
     */
    public function completeMission1(
        Captain $captain
    ): DailyTreasureProgress {

        $progress =
            $this->getOrCreate($captain);


        $progress->update([

            'mission1_completed' => true,

        ]);


        return $progress;
    }


    /**
     * Marca a missão 2 como concluída
     * e libera o baú da jornada atual.
     */
    public function completeMission2(
        Captain $captain
    ): DailyTreasureProgress {

        $progress =
            $this->getOrCreate($captain);


        $progress->update([

            'mission2_completed' => true,

            'treasure_available' => true,

            'treasure_collected' => false,

        ]);


        return $progress;
    }
}
