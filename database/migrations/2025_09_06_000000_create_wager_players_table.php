<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagerPlayersTable extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wager_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wager_choice_id')->nullable()->constrained('wager_choices')->onDelete('set null');
            $table->integer('bet_amount')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->unique(['wager_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_players');
    }
}
