<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            if (! Schema::hasColumn('wager_players', 'bet_amount')) {
                $table->integer('bet_amount')->nullable()->default(0);
            }
        });

        // Set NOT NULL after in separate statement
        DB::statement('ALTER TABLE wager_players ALTER COLUMN bet_amount SET NOT NULL');
    }

    public function down()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            $table->dropColumn('bet_amount');
        });
    }
};
