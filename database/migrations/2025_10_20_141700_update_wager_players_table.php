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
        }, 1);

        if (! Schema::hasTable('wager_players')) {
            \Log::warning('wager_players table does not exist, skipping migration');
            return;
        }

        Schema::table('wager_players', function (Blueprint $table) {
            // Add choice_id column if not exists
            if (! Schema::hasColumn('wager_players', 'choice_id')) {
                try {
                    $table->foreignId('choice_id')->nullable()->after('user_id');
                    \Log::info('Added choice_id column to wager_players');
                } catch (\Exception $e) {
                    \Log::error('Failed to add choice_id column to wager_players', ['error' => $e->getMessage()]);
                    throw $e;
                }
            }

            if (! Schema::hasColumn('wager_players', 'potential_payout')) {
                try {
                    $table->integer('potential_payout')->nullable()->after('bet_amount');
                    \Log::info('Added potential_payout column to wager_players');
                } catch (\Exception $e) {
                    \Log::error('Failed to add potential_payout column to wager_players', ['error' => $e->getMessage()]);
                    throw $e;
                }
            }

            if (! Schema::hasColumn('wager_players', 'actual_payout')) {
                try {
                    $table->integer('actual_payout')->nullable()->after('potential_payout');
                    \Log::info('Added actual_payout column to wager_players');
                } catch (\Exception $e) {
                    \Log::error('Failed to add actual_payout column to wager_players', ['error' => $e->getMessage()]);
                    throw $e;
                }
            }

            if (Schema::hasTable('wager_choices') && Schema::hasColumn('wager_players', 'choice_id')) {
                try {
                    $constraintExists = DB::selectOne("
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'wager_players_choice_id_foreign'
                        AND table_name = 'wager_players'
                    ");

                    if (! $constraintExists) {
                        $table->foreign('choice_id')
                            ->references('id')
                            ->on('wager_choices')
                            ->onDelete('set null');
                        \Log::info('Added wager_players_choice_id_foreign constraint to wager_players');
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to add wager_players_choice_id_foreign constraint', ['error' => $e->getMessage()]);
                    throw $e;
                }
            }
        });
    }

    public function down()
    {
        DB::transaction(function () {
        }, 1);

        if (! Schema::hasTable('wager_players')) {
            \Log::warning('wager_players table does not exist, skipping rollback');
            return;
        }

        Schema::table('wager_players', function (Blueprint $table) {
            try {
                $constraintExists = DB::selectOne("
                    SELECT 1 FROM information_schema.table_constraints
                    WHERE constraint_name = 'wager_players_choice_id_foreign'
                    AND table_name = 'wager_players'
                ");

                if ($constraintExists) {
                    $table->dropForeign(['choice_id']);
                    \Log::info('Dropped wager_players_choice_id_foreign constraint from wager_players');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to drop wager_players_choice_id_foreign constraint', ['error' => $e->getMessage()]);
            }

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
