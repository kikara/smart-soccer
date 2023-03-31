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
        $games = \App\Models\Game::all();

        foreach ($games as $game) {
            \App\Models\GameUser::create([
                'game_id' => $game->id,
                'user_id' => $game->gamerOne,
            ]);
            \App\Models\GameUser::create([
                'game_id' => $game->id,
                'user_id' => $game->gamerTwo,
            ]);
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
