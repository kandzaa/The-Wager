<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagerBetsTable extends Migration
{
    public function up()
    {
        Schema::create('wager_bets', function (Blueprint $table) {
            $table->id('wager_bet_id');
            $table->integer('wager_id');
            $table->integer('wager_choice_id');
            $table->integer('wager_player_id');
            $table->integer('bet_amount');
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->integer('actual_payout')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_bets');
        Log::info('Dropped wager_bets table');
    }
}
