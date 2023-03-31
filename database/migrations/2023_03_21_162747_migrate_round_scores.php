<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Round::chunk(200, function (Collection $rounds) {
            foreach ($rounds as $round) {
                \App\Models\RoundScore::create([
                    'round_id' => $round->id,
                    'user_id' => $round->gamerOne,
                    'score' => $round->countOne,
                ]);

                \App\Models\RoundScore::create([
                    'round_id' => $round->id,
                    'user_id' => $round->gamerTwo,
                    'score' => $round->countTwo,
                ]);
            }
        });
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
