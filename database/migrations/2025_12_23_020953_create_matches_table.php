<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            // Estado do match
            // waiting  -> aguardando segundo jogador
            // ready    -> dois jogadores prontos
            // playing  -> jogo iniciado (Gamezop)
            // finished -> jogo encerrado
            $table->string('status')->default('waiting');

            // Slots
            $table->foreignId('slot_1_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('slot_2_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 👇 ESSENCIAL
            $table->boolean('ready_slot_1')->default(false);
            $table->boolean('ready_slot_2')->default(false);

            $table->string('room_id')->nullable()->unique();
            $table->foreignId('winner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            // Código de convite (Fluxo C – futuro)
            $table->string('invite_code')->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
