<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarService
{
    /**
     * Faz download do avatar remoto e salva localmente (se fornecido).
     */
    public function downloadAndStore(?string $avatarUrl): ?string
    {
        if (!$avatarUrl) {
            return null;
        }

        try {
            $imageContent = @file_get_contents($avatarUrl);

            if (!$imageContent) {
                Log::warning("Avatar não pôde ser baixado: {$avatarUrl}");
                return null;
            }

            $filename = 'avatar_' . Str::uuid() . '.jpg';
            $path = 'avatars/' . $filename;

            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Exception $e) {
            Log::error("Erro ao salvar avatar: " . $e->getMessage());
            return null;
        }
    }
}
