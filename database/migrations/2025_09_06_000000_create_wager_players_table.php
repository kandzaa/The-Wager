<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagerPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('wager_players', function (Blueprint $table) {
            $table->id('wager_player_id');
            $table->integer('wager_id');
            $table->integer('user_id');
            $table->integer('bet_amount')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_players');
    }
}
