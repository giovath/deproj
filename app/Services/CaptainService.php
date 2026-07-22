<?php

namespace App\Services;

use App\Models\Captain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CaptainService
{
    public function getOrCreate(): Captain
    {
        /*
        |--------------------------------------------------------------------------
        | Usuário autenticado
        |--------------------------------------------------------------------------
        |
        | Sempre reutiliza o mesmo Captain.
        |
        */

        if (Auth::check()) {

            $captain = Captain::firstOrCreate(

                [
                    'user_id' => Auth::id(),
                ],

                [
                    'ref_code' => $this->generateRefCode(),
                ]

            );

            session([
                'captain_id' => $captain->id,
            ]);

            return $captain;
        }

        /*
        |--------------------------------------------------------------------------
        | Visitante
        |--------------------------------------------------------------------------
        */

        if (session()->has('captain_id')) {

            $captain = Captain::find(
                session('captain_id')
            );

            if ($captain) {
                return $captain;
            }
        }

        $captain = Captain::create([

            'ref_code' => $this->generateRefCode(),

        ]);

        session([
            'captain_id' => $captain->id,
        ]);

        return $captain;
    }


    public function current(): ?Captain
    {
        if (Auth::check()) {

            return Captain::where(
                'user_id',
                Auth::id()
            )->first();
        }

        if (!session()->has('captain_id')) {
            return null;
        }

        return Captain::find(
            session('captain_id')
        );
    }


    protected function generateRefCode(): string
    {
        do {

            $code = Str::upper(
                Str::random(8)
            );
        } while (

            Captain::where(
                'ref_code',
                $code
            )->exists()

        );

        return $code;
    }
}
