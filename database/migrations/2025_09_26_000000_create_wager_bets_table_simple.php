<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up()
    {
        Schema::create('wager_bets', function (Blueprint $table) {
            $table->id();

            // Explicitly define the columns first
            $table->unsignedBigInteger('wager_id');
            $table->unsignedBigInteger('wager_player_id')->nullable();
            $table->unsignedBigInteger('wager_choice_id');

            $table->decimal('bet_amount', 15, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('wager_id')->references('id')->on('wagers')->onDelete('cascade');
            $table->foreign('wager_player_id')->references('id')->on('wager_players')->onDelete('cascade');
            $table->foreign('wager_choice_id')->references('id')->on('wager_choices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_bets');
    }
};
