<?php

namespace App\Services;

use Illuminate\Support\Collection;

class GamesCuratorService
{
    // 🔒 Lista controlada de jogos multiplayer válidos
    public function multiplayerOptions(): Collection
    {
        return collect(config('gamezop_games.sync'));
    }

    // ✅ Validação da escolha do usuário
    public function isValidMultiplayer(string $code): bool
    {
        return $this->multiplayerOptions()->contains($code);
    }

    // ⚠️ Mantido para compatibilidade / fallback
    public function pickGameCode(): string
    {
        $games = config('gamezop_games.sync');
        return $games[array_rand($games)];
    }
}
