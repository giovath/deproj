<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_rankings', function (Blueprint $table) {

            $table->id();


            $table->foreignId('captain_id')
                ->constrained()
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Identificador da semana
            |--------------------------------------------------------------------------
            |
            | Exemplo:
            | 2026-W30
            |
            */
            $table->string('week_key');


            /*
            |--------------------------------------------------------------------------
            | Posição final
            |--------------------------------------------------------------------------
            |
            | 1 = campeão
            | 2 = segundo
            | 3 = terceiro
            |
            */
            $table->integer('position');


            /*
            |--------------------------------------------------------------------------
            | Pontuação conquistada
            |--------------------------------------------------------------------------
            */
            $table->integer('relics');


            /*
            |--------------------------------------------------------------------------
            | Valor da recompensa
            |--------------------------------------------------------------------------
            */
            $table->decimal('reward', 10, 2)
                ->default(0);


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Evita fechamento duplicado
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'captain_id',
                'week_key'
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('weekly_rankings');
    }
};
