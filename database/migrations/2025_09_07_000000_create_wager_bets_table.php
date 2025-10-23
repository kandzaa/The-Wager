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
            $table->id();
            $table->unsignedBigInteger('wager_id');
            $table->unsignedBigInteger('wager_choice_id');
            $table->unsignedBigInteger('wager_player_id');
            $table->integer('bet_amount');
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->integer('actual_payout')->nullable();
            $table->timestamps();
        });
        Log::info('Created wager_bets table');
    }

    public function down()
    {
        Schema::dropIfExists('wager_bets');
        Log::info('Dropped wager_bets table');
    }
}
