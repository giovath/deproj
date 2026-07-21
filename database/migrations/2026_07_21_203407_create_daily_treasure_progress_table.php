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
        Schema::create('daily_treasure_progress', function (Blueprint $table) {

            $table->id();

            $table->foreignId('captain_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('mission1_completed')
                ->default(false);

            $table->boolean('mission2_completed')
                ->default(false);

            $table->boolean('treasure_available')
                ->default(false);

            $table->boolean('treasure_collected')
                ->default(false);


            $table->timestamp('expires_at')
                ->nullable();


            $table->timestamps();


            $table->unique('captain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_treasure_progress');
    }
};
