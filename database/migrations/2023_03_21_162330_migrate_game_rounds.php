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
        $gameRounds = \App\Models\GameRound::all();

        foreach ($gameRounds as $gameRound) {
            $round = \App\Models\Round::find($gameRound->roundId);

            $round->game_id  = $gameRound->gameId;

            $round->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
