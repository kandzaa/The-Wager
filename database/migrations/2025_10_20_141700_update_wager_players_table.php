<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disable transactions to avoid SQLSTATE[25P02]
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION READ WRITE');

        if (! Schema::hasTable('wager_players')) {
            \Log::warning('wager_players table does not exist, skipping migration');
            return;
        }

        Schema::table('wager_players', function (Blueprint $table) {
            // Add choice_id column if not exists
            if (! Schema::hasColumn('wager_players', 'choice_id')) {
                $table->foreignId('choice_id')->nullable()->after('user_id');
                \Log::info('Added choice_id column to wager_players');
            } else {
                \Log::info('choice_id column already exists in wager_players');
            }

            // Add potential_payout column if not exists
            if (! Schema::hasColumn('wager_players', 'potential_payout')) {
                $table->integer('potential_payout')->nullable()->after('bet_amount');
                \Log::info('Added potential_payout column to wager_players');
            } else {
                \Log::info('potential_payout column already exists in wager_players');
            }

            // Add actual_payout column if not exists
            if (! Schema::hasColumn('wager_players', 'actual_payout')) {
                $table->integer('actual_payout')->nullable()->after('potential_payout');
                \Log::info('Added actual_payout column to wager_players');
            } else {
                \Log::info('actual_payout column already exists in wager_players');
            }

            // Add foreign key constraint for choice_id
            if (Schema::hasTable('wager_choices') && Schema::hasColumn('wager_players', 'choice_id')) {
                $constraintExists = DB::selectOne("
                    SELECT 1 FROM information_schema.table_constraints
                    WHERE constraint_name = 'wager_players_choice_id_foreign'
                    AND table_name = 'wager_players'
                ");

                if (! $constraintExists) {
                    try {
                        $table->foreign('choice_id')
                            ->references('id')
                            ->on('wager_choices')
                            ->onDelete('set null');
                        \Log::info('Added wager_players_choice_id_foreign constraint to wager_players');
                    } catch (\Exception $e) {
                        \Log::error('Failed to add wager_players_choice_id_foreign constraint', ['error' => $e->getMessage()]);
                    }
                } else {
                    \Log::info('wager_players_choice_id_foreign constraint already exists');
                }
            }
        });
    }

    public function down()
    {
        // Disable transactions for rollback
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION READ WRITE');

        if (! Schema::hasTable('wager_players')) {
            \Log::warning('wager_players table does not exist, skipping rollback');
            return;
        }

        Schema::table('wager_players', function (Blueprint $table) {
            // Drop foreign key constraint
            $constraintExists = DB::selectOne("
                SELECT 1 FROM information_schema.table_constraints
                WHERE constraint_name = 'wager_players_choice_id_foreign'
                AND table_name = 'wager_players'
            ");

            if ($constraintExists) {
                try {
                    $table->dropForeign(['choice_id']);
                    \Log::info('Dropped wager_players_choice_id_foreign constraint from wager_players');
                } catch (\Exception $e) {
                    \Log::error('Failed to drop wager_players_choice_id_foreign constraint', ['error' => $e->getMessage()]);
                }
            }

            // Drop columns
            $columns = ['choice_id', 'potential_payout', 'actual_payout'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('wager_players', $column)) {
                    try {
                        $table->dropColumn($column);
                        \Log::info("Dropped $column column from wager_players");
                    } catch (\Exception $e) {
                        \Log::error("Failed to drop $column column from wager_players", ['error' => $e->getMessage()]);
                    }
                }
            }
        });
    }
};
