<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        // Disable transaction handling for this migration
        DB::statement('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');

        Schema::create('wager_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wager_id');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Add the foreign key in a separate statement
        DB::statement('
        ALTER TABLE wager_choices
        ADD CONSTRAINT wager_choices_wager_id_foreign
        FOREIGN KEY (wager_id)
        REFERENCES wagers (id)
        ON DELETE CASCADE
    ');
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
    }
}
