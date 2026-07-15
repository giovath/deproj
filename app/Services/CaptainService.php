<?php

namespace App\Services;

use App\Models\Captain;
use Illuminate\Support\Str;

class CaptainService
{
    public function getOrCreate(): Captain
    {
        if (session()->has('captain_id')) {

            $captain = Captain::find(
                session('captain_id')
            );

            if ($captain) {
                return $captain;
            }
        }


        $captain = Captain::create([

            'ref_code' => Str::upper(
                Str::random(8)
            ),

        ]);


        session([
            'captain_id' => $captain->id
        ]);


        return $captain;
    }

    public function current()
    {
        if (!session()->has('captain_id')) {
            return null;
        }

        return Captain::find(
            session('captain_id')
        );
    }
}
