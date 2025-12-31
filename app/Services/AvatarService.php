<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarService
{
    public function downloadAndStore(string $url): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get($url);

            if (!$response->successful()) {
                Log::warning("Falha ao baixar avatar TikTok: HTTP " . $response->status());
                return null;
            }

            $imageContent = $response->body();
            $avatarFilename = 'avatar_' . Str::random(20) . '.jpg';

            Storage::disk('public')->put('avatars/' . $avatarFilename, $imageContent);

            return 'avatars/' . $avatarFilename;
        } catch (\Exception $e) {
            Log::warning("Erro ao baixar/salvar avatar TikTok: " . $e->getMessage());
            return null;
        }
    }
}
