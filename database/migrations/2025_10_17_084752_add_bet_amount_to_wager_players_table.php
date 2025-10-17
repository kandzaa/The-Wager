<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBetAmountToWagerPlayersTable extends Migration
{
    public function up()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            if (! Schema::hasColumn('wager_players', 'bet_amount')) {
                $table->integer('bet_amount')->default(0);
            }
        });

        DB::statement('ALTER TABLE wager_players ALTER COLUMN bet_amount SET NOT NULL;');
    }

    public function down()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            $table->dropColumn('bet_amount');
        });
    }
}
