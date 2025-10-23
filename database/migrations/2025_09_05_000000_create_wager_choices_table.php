<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

            // First create the column
            $table->index('wager_id');
        });

        // Then add the foreign key in a separate transaction
        DB::transaction(function () {
            Schema::table('wager_choices', function (Blueprint $table) {
                $table->foreign('wager_id')
                    ->references('id')
                    ->on('wagers')
                    ->onDelete('cascade');
            });
        });
    }
    public function down()
    {
        Schema::dropIfExists('wager_choices');
    }
}
