<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagerBetsTable extends Migration
{
    public function up()
    {
        Schema::create('wager_bets', function (Blueprint $table) {
            $table->id();
            // Assuming wager_id, wager_choice_id, and wager_player_id refer to the 'id' columns of their respective tables.
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
            $table->foreignId('wager_choice_id')->constrained('wager_choices')->onDelete('cascade');
            $table->foreignId('wager_player_id')->constrained('wager_players')->onDelete('cascade');

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
    }
}
