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

        $userInfoResponse = Http::withHeaders([
            'Authorization' => "Bearer {$tiktokUser->token}",
            'Content-Type' => 'application/json'
        ])->post('https://open.tiktokapis.com/v2/user/info/', [
            'fields' => 'display_name,avatar_large_url'
        ]);

        $userData = $userInfoResponse->json()['data']['user'] ?? [];

        $name = $userData['display_name']
            ?? $tiktokUser->getNickname()
            ?? 'TikTok User';

        $avatar = $userData['avatar_large_url']
            ?? $tiktokUser->getAvatar()
            ?? null;

        $email = $tiktokUser->getId() . '@tiktok.local';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'avatar_url' => $avatar,
                'password' => bcrypt(Str::random(32))
            ]
        );

        UserProvider::updateOrCreate(
            [
                'user_id'  => $user->id,
                'provider' => 'tiktok',
            ],
            [
                'provider_user_id' => $tiktokUser->getId(),
                'nickname' => $name,
                'avatar_url' => $avatar,
                'access_token' => $tiktokUser->token,
                'refresh_token' => $tiktokUser->refreshToken ?? null,
                'raw_payload' => $tiktokUser->user ?? null
            ]
        );

        Auth::login($user);

        $match = $arena->handle($user);

        return redirect('/')->with('match_id', $match->id);
    }
}
