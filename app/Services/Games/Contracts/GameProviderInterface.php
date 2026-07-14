<?php

namespace App\Services\Games\Contracts;

use Illuminate\Support\Collection;

interface GameProviderInterface
{
    public function games(int $page = 1): Collection;
}
