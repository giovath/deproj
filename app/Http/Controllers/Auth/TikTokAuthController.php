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

        // --- Dados básicos disponíveis de imediato
        $providerUserId = $tiktokUser->getId();
        $email = $providerUserId . '@tiktok.local';

        // --- Chamada TikTok API para dados completos
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$tiktokUser->token}",
            'Content-Type' => 'application/json'
        ])->post('https://open.tiktokapis.com/v2/user/info/', [
            'fields' => 'display_name,avatar_large_url'
        ]);

        $userData = $response->json()['data']['user'] ?? [];

        $name = $userData['display_name']
            ?? $tiktokUser->getNickname()
            ?? 'TikTok User';

        $avatar = $userData['avatar_large_url'] ?? null;

        // Criar/Buscar usuário no sistema
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // Atualizar provider
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
                'raw_payload' => $tiktokUser->user ?? null,
            ]
        );

        Auth::login($user);

        $match = $arena->handle($user);

        return redirect('/')->with('match_id', $match->id);
    }
}
