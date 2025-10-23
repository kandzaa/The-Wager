<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wager_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wager_id');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::table('wager_choices', function (Blueprint $table) {
            $table->foreign('wager_id')
                ->references('id')
                ->on('wagers')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
    }
}
