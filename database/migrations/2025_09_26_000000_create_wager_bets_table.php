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
            $table->bigIncrements('id');
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
            $table->foreignId('wager_choice_id')->constrained('wager_choices')->onDelete('cascade');
            $table->foreignId('wager_player_id')->constrained('wager_players')->onDelete('cascade');
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
    }
}
