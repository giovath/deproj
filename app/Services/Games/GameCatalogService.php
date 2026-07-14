<?php

namespace App\Services\Games;

use App\Services\Games\Contracts\GameProviderInterface;
use Illuminate\Support\Collection;

class GameCatalogService
{
    public function __construct(
        protected GameProviderInterface $provider
    ) {}

    public function games(int $page = 1): Collection
    {
        return $this->provider->games($page);
    }
}
