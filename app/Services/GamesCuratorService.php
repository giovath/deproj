<?php

namespace App\Services;

use Illuminate\Support\Collection;

class GamesCuratorService
{
    /**
     * 🎮 Todos os jogos registrados no sistema
     */
    protected function allGames(): Collection
    {
        return collect(config('gamezop_games'))
            ->flatten(1)
            ->values();
    }

    /**
     * 🎮 Jogos multiplayer disponíveis (>= 2 jogadores)
     */
    public function availableGames(): Collection
    {
        return $this->allGames()
            ->filter(
                fn($game) =>
                isset($game['max_players']) && $game['max_players'] >= 2
            )
            ->values();
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
     * 🎲 Fallback futuro (random)
     */
    public function pickGameCode(): string
    {
        return $this->multiplayerOptions()->random();
    }
}
