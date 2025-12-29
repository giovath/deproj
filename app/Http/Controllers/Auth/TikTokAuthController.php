<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Services\EnterArenaService;

class TikTokAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('tiktok')->scopes(['user.info.basic'])->redirect();
    }

    public function callback(EnterArenaService $arena)
    {
        try {
            $tiktokUser = Socialite::driver('tiktok')->user();
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Falha ao autenticar no TikTok.');
        }

        $email = $tiktokUser->getId() . '@tiktok.local';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $tiktokUser->getNickname() ?? 'TikTok User',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        UserProvider::updateOrCreate(
            [
                'user_id'  => $user->id,
                'provider' => 'tiktok',
            ],
            [
                'provider_user_id' => $tiktokUser->getId(),
                'nickname' => $tiktokUser->getNickname(),
                'avatar_url' => $tiktokUser->getAvatar(),
                'access_token' => $tiktokUser->token,
                'refresh_token' => $tiktokUser->refreshToken,
                'raw_payload' => $tiktokUser->user ?? null
            ]
        );

        Auth::login($user);

        $match = $arena->handle($user);

        return redirect('/')->with('match_id', $match->id);
    }
}
