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

            // O usuário já possui um capitão?
            $existingCaptain = Captain::where(
                'user_id',
                Auth::id()
            )->first();

            if ($existingCaptain) {

                session([
                    'captain_id' => $existingCaptain->id,
                ]);

                return $existingCaptain;
            }

            // Ainda não possui.
            // Vamos aproveitar o capitão anônimo.
            if (session()->has('captain_id')) {

                $guestCaptain = Captain::find(session('captain_id'));

                if (
                    $guestCaptain &&
                    !$guestCaptain->user_id
                ) {

                    $guestCaptain->user_id = Auth::id();
                    $guestCaptain->save();

                    $guestCaptain->refresh();

                    session([
                        'captain_id' => $guestCaptain->id,
                    ]);

                    return $guestCaptain;
                }
            }

            // Não existe nenhum, cria um novo.
            $captain = Captain::create([
                'user_id' => Auth::id(),
                'ref_code' => $this->generateRefCode(),
            ]);

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
