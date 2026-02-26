<?php

namespace App\Services;

use Illuminate\Support\Collection;

class GamesCuratorService
{
    /**
     * 🎮 Jogos multiplayer disponíveis para escolha
     */
    public function availableGames(): Collection
    {
        return collect(
            array_merge(
                config('gamezop_games.sync_core', []),
                config('gamezop_games.sync_casual', [])
            )
        )->values();
    }

    /**
     * 🔒 Lista controlada de códigos válidos (multiplayer)
     */
    public function multiplayerOptions(): Collection
    {
        return $this->availableGames()->pluck('code');
    }

    /**
     * ✅ Validação da escolha do usuário
     */
    public function isValidMultiplayer(string $code): bool
    {
        return $this->multiplayerOptions()->contains($code);
    }

    /**
     * ⚠️ Fallback futuro (random)
     */
    public function pickGameCode(): string
    {
        return $this->multiplayerOptions()->random();
    }
}
