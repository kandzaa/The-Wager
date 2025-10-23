<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        Schema::create('wager_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
    }
}
