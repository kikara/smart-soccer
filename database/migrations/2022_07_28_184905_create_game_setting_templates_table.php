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
        Schema::create('game_setting_templates', function (Blueprint $table) {
            $table->id();
            $table->string('mode')->default('');
            $table->string('side')->default('');
            $table->integer('user_id')->nullable();
            $table->boolean('side_change')->default(false);
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
        Schema::dropIfExists('game_setting_templates');
    }
};
