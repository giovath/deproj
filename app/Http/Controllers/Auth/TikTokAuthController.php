<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\EnterArenaService;


class TikTokAuthController extends Controller
{
    /**
     * Redireciona para o OAuth do TikTok
     */
    public function redirect(Request $request)
    {
        $state = Str::random(40);
        session(['tiktok_oauth_state' => $state]);

        $query = http_build_query([
            'client_key'    => config('services.tiktok.client_key'),
            'response_type' => 'code',
            'scope'         => 'user.info.basic',
            'redirect_uri'  => route('auth.tiktok.callback'),
            'state'         => $state,
        ]);

        return redirect('https://www.tiktok.com/v2/auth/authorize/?' . $query);
    }

    /**
     * Callback do TikTok
     */
    public function callback(Request $request, EnterArenaService $arena)

    {
        // 1️⃣ Proteção CSRF
        if ($request->state !== session('tiktok_oauth_state')) {
            abort(403, 'Invalid OAuth state');
        }

        session()->forget('tiktok_oauth_state');

        if ($request->has('error')) {
            return redirect('/')->withErrors('Login TikTok cancelado');
        }

        // 2️⃣ Troca code por token
        $tokenResponse = Http::asForm()->post(
            'https://open.tiktokapis.com/v2/oauth/token/',
            [
                'client_key'    => config('services.tiktok.client_key'),
                'client_secret' => config('services.tiktok.client_secret'),
                'code'          => $request->code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => route('auth.tiktok.callback'),
            ]
        )->json();

        if (!isset($tokenResponse['access_token'])) {
            abort(500, 'TikTok token error');
        }

        // 3️⃣ Buscar dados do usuário
        $userResponse = Http::withToken($tokenResponse['access_token'])
            ->get('https://open.tiktokapis.com/v2/user/info/', [
                'fields' => 'open_id,username,avatar_url',
            ])
            ->json();

        $ttUser = $userResponse['data']['user'];

        // 4️⃣ Criar ou recuperar usuário local
        $user = User::firstOrCreate(
            [
                'email' => $ttUser['open_id'] . '@tiktok.local',
            ],
            [
                'name'     => $ttUser['username'] ?? 'TikTok User',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // 5️⃣ Vincular provider
        UserProvider::updateOrCreate(
            [
                'user_id'  => $user->id,
                'provider' => 'tiktok',
            ],
            [
                'provider_user_id' => $ttUser['open_id'],
                'nickname'         => $ttUser['username'] ?? null,
                'avatar_url'       => $ttUser['avatar_url'] ?? null,
                'access_token'     => $tokenResponse['access_token'],
                'refresh_token'    => $tokenResponse['refresh_token'] ?? null,
                'token_expires_at' => now()->addSeconds($tokenResponse['expires_in'] ?? 0),
                'raw_payload'      => $userResponse,
            ]
        );

        Auth::login($user);

        return redirect('/');
    }
}
