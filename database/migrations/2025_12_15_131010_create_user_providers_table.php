<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_providers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('provider'); // tiktok, google, etc
            $table->string('provider_user_id');

            // Dados públicos
            $table->string('nickname')->nullable();
            $table->string('avatar_url')->nullable();

            // Tokens (OAuth)
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();

            // Payload bruto do provider
            $table->json('raw_payload')->nullable();

            $table->timestamps();

            $table->unique(['provider', 'provider_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_providers');
    }
};
