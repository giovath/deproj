<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProvider;
use App\Services\LinkCaptainService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Services\TreasureProgressService;

class TikTokAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('tiktok')
            ->scopes(['user.info.basic'])
            ->redirect();
    }

    public function callback(
        LinkCaptainService $linkCaptain,
        TreasureProgressService $progressService
    ) {
        try {

            $tiktokUser = Socialite::driver('tiktok')->user();
        } catch (\Exception $e) {

            Log::error(
                'Erro ao obter usuário TikTok: ' .
                    $e->getMessage()
            );

            return redirect('/')
                ->withErrors('Falha ao autenticar no TikTok.');
        }

        $providerUserId = $tiktokUser->getId();

        $userData = $tiktokUser->user ?? [];

        $name =
            $userData['display_name']
            ?? 'TikTok User';

        $avatar =
            $userData['avatar_large_url']
            ?? null;

        // TikTok não fornece e-mail.
        $email = $providerUserId . '@tiktok.local';


        /*
        |--------------------------------------------------------------------------
        | Usuário
        |--------------------------------------------------------------------------
        */

        $user = User::firstOrCreate(

            [
                'email' => $email,
            ],

            [
                'name' => $name,
                'password' => bcrypt(Str::random(32)),
            ]

        );

        $user->update([
            'name' => $name,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Provider
        |--------------------------------------------------------------------------
        */

        UserProvider::updateOrCreate(

            [
                'user_id' => $user->id,
                'provider' => 'tiktok',
            ],

            [
                'provider_user_id' => $providerUserId,
                'nickname' => $name,
                'avatar_url' => $avatar,
                'access_token' => $tiktokUser->token,
                'refresh_token' => $tiktokUser->refreshToken ?? null,
                'raw_payload' => $userData,
            ]

        );


        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Auth::login($user);


        /*
        |--------------------------------------------------------------------------
        | Vincula o Capitão Anônimo
        |--------------------------------------------------------------------------
        */

        $linkCaptain->execute(
            $user,
            $progressService
        );


        /*
        |--------------------------------------------------------------------------
        | Volta para o Porto
        |--------------------------------------------------------------------------
        */

        return redirect('/porto');
    }
}
