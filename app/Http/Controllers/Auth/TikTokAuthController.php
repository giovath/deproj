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
use Illuminate\Support\Facades\Log;


class TikTokAuthController extends Controller
{
    /**
     * Redireciona para o OAuth do TikTok
     */
    public function redirect(Request $request)
    {
        $state = hash_hmac('sha256', Str::random(40), config('app.key'));

        return redirect()->away('https://www.tiktok.com/v2/auth/authorize/?' . http_build_query([
            'client_key'    => config('services.tiktok.client_key'),
            'response_type' => 'code',
            'scope' => 'user.info.profile',
            'redirect_uri'  => route('auth.tiktok.callback'),
            'state'         => $state
        ]));
    }



    /**
     * Callback do TikTok
     */
    public function callback(Request $request, EnterArenaService $arena)
    {
        if (!$request->has('state')) {
            abort(403, 'Missing OAuth state');
        }

        Log::info('TikTok OAuth callback debug', [
            'received_code' => $request->code ?? 'none',
            'redirect_uri' => route('auth.tiktok.callback'),
            'client_key_present' => config('services.tiktok.client_key') ? true : false,
            'state_received' => $request->state,
        ]);

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
            Log::error('TikTok token exchange failed', [
                'token_response' => $tokenResponse,
                'request_code' => $request->code ?? 'none',
                'redirect_uri' => route('auth.tiktok.callback')
            ]);

            return redirect('/')
                ->withErrors('Erro ao obter token do TikTok — confira permissões e escopos.');
        }


        $accessToken = $tokenResponse['access_token'];

        // 3️⃣ Buscar dados do usuário
        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])
            ->send('POST', 'https://open.tiktokapis.com/v2/user/info/', [
                'json' => [
                    'fields' => [
                        'open_id',
                        'avatar_url',
                        'display_name',
                    ],
                ]
            ])
            ->json();



        // 🔍 Validação real
        if (
            !isset($userResponse['data']) ||
            empty($userResponse['data']['open_id'])
        ) {
            Log::error('TikTok user data missing', [
                'token' => $tokenResponse,
                'user_response' => $userResponse
            ]);

            return redirect('/')
                ->withErrors('Permissão insuficiente para acessar dados do TikTok. Conceda as permissões novamente.');
        }

        $ttUser = $userResponse['data'];


        $user = User::firstOrCreate(
            [
                'email' => $ttUser['open_id'] . '@tiktok.local',
            ],
            [
                'name'     => $ttUser['display_name'] ?? 'TikTok User',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // Vincular provider
        UserProvider::updateOrCreate(
            [
                'user_id'  => $user->id,
                'provider' => 'tiktok',
            ],
            [
                'provider_user_id' => $ttUser['open_id'],
                'nickname'         => $ttUser['display_name'] ?? null,
                'avatar_url'       => $ttUser['avatar_url'] ?? null,
                'access_token'     => $accessToken,
                'refresh_token'    => $tokenResponse['refresh_token'] ?? null,
                'token_expires_at' => now()->addSeconds($tokenResponse['expires_in'] ?? 0),
                'raw_payload'      => $userResponse,
            ]
        );



        Auth::login($user);

        $match = $arena->handle($user);

        return redirect('/')
            ->with('match_id', $match->id);
    }
}
