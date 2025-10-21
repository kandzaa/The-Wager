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
            $table->foreignId('wager_id')->constrained()->onDelete('cascade');
            $table->foreignId('wager_choice_id')->constrained()->onDelete('cascade');
            $table->foreignId('wager_player_id')->constrained()->onDelete('cascade');
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
};
