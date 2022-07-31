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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->integer('gamerOne')->default(0);
            $table->integer('gamerTwo')->default(0);
            $table->integer('countOne')->default(0);
            $table->integer('countTwo')->default(0);
            $table->integer('winner')->default(0);
            $table->integer('totalTime')->default(0);
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
        Schema::dropIfExists('rounds');
    }
};
