<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        Schema::create('wager_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wager_id');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Log::info('Created wager_choices table');
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
        Log::info('Dropped wager_choices table');
    }
}
