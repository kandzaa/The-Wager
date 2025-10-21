<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disable transactions
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION READ WRITE');

        // Clean wager_players duplicates
        DB::statement("
            DELETE FROM wager_players
            WHERE id IN (
                SELECT id FROM (
                    SELECT id, ROW_NUMBER() OVER (PARTITION BY wager_id, user_id ORDER BY id DESC) as rnum
                    FROM wager_players
                ) t WHERE t.rnum > 1
            )
        ");

        // Clean invalid actual_payout in wager_bets
        DB::statement("
            UPDATE wager_bets
            SET actual_payout = NULL
            WHERE actual_payout IS NOT NULL
            AND actual_payout::text !~ '^[0-9]+$'
        ");

        // Fix wager_bets actual_payout type
        Schema::table('wager_bets', function (Blueprint $table) {
            $table->integer('actual_payout')->nullable()->change();
        });

        // Ensure unique constraint on wager_players
        Schema::table('wager_players', function (Blueprint $table) {
            $table->unique(['wager_id', 'user_id'], 'wager_players_wager_id_user_id_unique');
        });

        // Drop unexpected constraints
        DB::statement("ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_choice_id_foreign;");
    }

    public function down()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            $table->dropUnique('wager_players_wager_id_user_id_unique');
        });
    }
};
