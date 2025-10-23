<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagerPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('wager_players', function (Blueprint $table) {
            $table->id('wager_player_id');
            $table->id('wager_id');
            $table->id('user_id');
            $table->integer('bet_amount')->default(0);
            $table->timestamps();
        });
        Log::info('Created wager_players table');
    }

    public function down()
    {
        Schema::dropIfExists('wager_players');
        Log::info('Dropped wager_players table');
    }
}
