<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::transaction(function () {
            // No-op, we just want to disable transactions
        }, 1); 

        if (!Schema::hasTable('wager_players')) {
            \Log::warning('wager_players table does not exist, skipping migration');
            return;
        }

        Schema::table('wager_players', function (Blueprint $table) {
            $table->unsignedBigInteger('choice_id')->nullable()->after('user_id');
            $table->integer('potential_payout')->nullable()->after('bet_amount');
            $table->integer('actual_payout')->nullable()->after('potential_payout');
        });
        if (Schema::hasTable('wager_choices')) {
            try {
                DB::statement('ALTER TABLE wager_players
                    ADD CONSTRAINT wager_players_choice_id_foreign
                    FOREIGN KEY (choice_id)
                    REFERENCES wager_choices(id)
                    ON DELETE SET NULL');
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                \Illuminate\Support\Facades\Log::error('Failed to add foreign key constraint: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('wager_players')) {
            Schema::table('wager_players', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['choice_id']);
                }
            });
        }

        // Then drop the columns
        Schema::table('wager_players', function (Blueprint $table) {
            $table->dropColumn(['choice_id', 'potential_payout', 'actual_payout']);
        });
    }
};
