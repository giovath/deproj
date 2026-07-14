<?php

namespace App\Services\Games\Providers;

use App\Data\Game;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use App\Services\Games\Contracts\GameProviderInterface;

class GamePixProvider implements GameProviderInterface
{
    public function games(int $page = 1): Collection
    {
        $response = Http::get(
            config('services.gamepix.feed'),
            [
                'sid'        => config('services.gamepix.sid'),
                'pagination' => config('services.gamepix.pagination'),
                'page'       => $page,
            ]
        );

        if (! $response->successful()) {
            return collect();
        }

        return collect($response->json('items'))
            ->map(function ($item) {

                return new Game(

                    id: $item['id'],

                    provider: 'gamepix',

                    title: $item['title'],

                    slug: $item['namespace'],

                    description: $item['description'] ?? '',

                    category: $item['category'] ?? 'other',

                    orientation: $item['orientation'] ?? 'landscape',

                    cover: $item['banner_image'] ?? '',

                    icon: $item['image'] ?? '',

                    playUrl: $item['url'],

                    width: (int) $item['width'],

                    height: (int) $item['height'],

                    quality: (float) $item['quality_score'],

                );
            });
    }
}
