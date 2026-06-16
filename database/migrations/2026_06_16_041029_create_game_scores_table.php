<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_scores', function (Blueprint $table) {

            $table->id();

            $table->string('player_uuid')->index();

            $table->string('game_code');

            $table->bigInteger('score')->default(0);

            $table->string('session_id')->nullable();

            $table->string('game_play_id')->nullable();

            $table->timestamp('played_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_scores');
    }
};
