<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('tournament_id')->index('tournamentId');
            $table->integer('user_id')->index('userId');
            $table->boolean('isWinner');
            $table->integer('game_id')->index('gameId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_tables');
    }
};
