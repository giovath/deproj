<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GamezopService
{
    protected string $baseUrl;
    protected string $token;
    protected string $lang;

    public function __construct()
    {
        $this->baseUrl = 'https://api.gamezop.com/v3/games';
        $this->token   = config('services.gamezop.token');
        $this->lang    = config('services.gamezop.lang', 'pt');
    }

    public function getAllGames(): array
    {
        return Cache::remember('gamezop_games_' . $this->lang, now()->addHours(12), function () {
            $response = Http::withToken($this->token)
                ->get($this->baseUrl, [
                    'lang' => $this->lang,
                ]);

            if ($response->failed()) {
                // Logável no futuro, por enquanto retorno seguro
                return [];
            }

            return $response->json('data', []);
        });
    }

    public function getGameByCode(string $code): ?array
    {
        $games = $this->getAllGames();

        foreach ($games as $game) {
            if (($game['code'] ?? null) === $code) {
                return $game;
            }
        }

        return null;
    }
}
