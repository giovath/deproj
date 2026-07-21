<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('captain_wallets', function (Blueprint $table) {

            $table->id();


            $table->foreignId('captain_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->integer('coins')
                ->default(0);


            $table->integer('participations')
                ->default(0);


            $table->integer('relics')
                ->default(0);

            $table->integer('weekly_relics')->default(0);


            $table->timestamps();


            $table->unique('captain_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('captain_wallets');
    }
};
