<?php

namespace App\Services\Games\Providers;

use App\Services\Games\Contracts\GameProviderInterface;
use Illuminate\Support\Collection;
use App\Data\Game;
use Illuminate\Support\Facades\Http;

class GamePixProvider implements GameProviderInterface
{
    public function games(int $page = 1): Collection
    {
        $response = Http::get(
            config('services.gamepix.feed'),
            [
                'sid' => config('services.gamepix.sid'),
                'pagination' => config('services.gamepix.pagination'),
                'page' => $page,
            ]
        );

        if (! $response->successful()) {
            return collect();
        }

        dd($response->json());
    }
}
