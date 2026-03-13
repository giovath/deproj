<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Services\EnterArenaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('tiktok')
            ->scopes(['user.info.basic'])
            ->redirect();
    }

    public function callback(EnterArenaService $arena)
    {
        try {
            $tiktokUser = Socialite::driver('tiktok')->user();
        } catch (\Exception $e) {
            Log::error('Erro ao obter usuário TikTok: ' . $e->getMessage());
            return redirect('/')->withErrors('Falha ao autenticar no TikTok.');
        }

        $providerUserId = $tiktokUser->getId();
        $userData = $tiktokUser->user ?? [];

        $name = $userData['display_name'] ?? 'TikTok User';
        $avatar = $userData['avatar_large_url'] ?? null;
        $email = $providerUserId . '@tiktok.local';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt(Str::random(32)),
            ]
        );

        $user->update(['name' => $name]);

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

        Auth::login($user);

        if (session()->has('invited_match_id')) {

            $match = \App\Models\GameMatch::find(session('invited_match_id'));

            if ($match && !$match->slot_2_user_id && $match->slot_1_user_id !== $user->id) {

                $match->slot_2_user_id = $user->id;
                $match->save();

                session(['match_id' => $match->id]);
            } else {

                // fallback caso o match esteja cheio ou inválido
                $match = $arena->handle($user);
                session(['match_id' => $match->id]);
            }

            session()->forget('invited_match_id');
        } else {

            $match = $arena->handle($user);
            session(['match_id' => $match->id]);
        }

        return redirect()->route('home');
    }
}
