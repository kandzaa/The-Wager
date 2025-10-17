<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wager_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('bet_amount')->default(0); // ← ADD THIS
            $table->timestamps();

            $table->unique(['wager_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_players');
    }
};
