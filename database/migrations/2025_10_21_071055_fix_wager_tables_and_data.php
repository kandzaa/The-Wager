<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure required tables exist
        Schema::create('wagers', function (Blueprint $table) {
            if (! Schema::hasTable('wagers')) {
                $table->bigIncrements('id');
                $table->integer('pot')->default(0);
                $table->string('status')->default('open');
                $table->timestamps();
            }
        });

        Schema::create('users', function (Blueprint $table) {
            if (! Schema::hasTable('users')) {
                $table->bigIncrements('id');
                $table->integer('balance')->default(0);
                $table->timestamps();
            }
        });

        Schema::create('wager_choices', function (Blueprint $table) {
            if (! Schema::hasTable('wager_choices')) {
                $table->bigIncrements('id');
                $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
                $table->integer('total_bet')->default(0);
                $table->string('label')->nullable();
                $table->timestamps();
            }
        });

        // Insert test data first
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION READ WRITE');
        if (! DB::table('wagers')->where('id', 1)->exists()) {
            DB::table('wagers')->insert([
                'id'         => 1,
                'pot'        => 0,
                'status'     => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info('Inserted test wager id=1');
        }

        if (! DB::table('wager_choices')->where('id', 1)->where('wager_id', 1)->exists()) {
            DB::table('wager_choices')->insert([
                'id'         => 1,
                'wager_id'   => 1,
                'total_bet'  => 0,
                'label'      => 'asd',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info('Inserted test wager_choice id=1 for wager_id=1');
        }

        if (! DB::table('users')->where('id', 1)->exists()) {
            DB::table('users')->insert([
                'id'         => 1,
                'balance'    => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info('Inserted test user id=1');
        }

        // Drop all constraints on wager_players
        DB::statement('ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_wager_id_foreign;');
        DB::statement('ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_user_id_foreign;');
        DB::statement('ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_choice_id_foreign;');
        DB::statement('ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_potential_payout_foreign;');
        DB::statement('ALTER TABLE wager_players DROP CONSTRAINT IF EXISTS wager_players_actual_payout_foreign;');
        \Log::info('Dropped all constraints from wager_players');

        // Drop unwanted columns
        $unwantedColumns = ['choice_id', 'potential_payout', 'actual_payout'];
        foreach ($unwantedColumns as $column) {
            if (Schema::hasColumn('wager_players', $column)) {
                Schema::table('wager_players', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
                \Log::info("Dropped column {$column} from wager_players");
            }
        }

        // Recreate wager_players table if necessary
        Schema::create('wager_players', function (Blueprint $table) {
            if (! Schema::hasTable('wager_players')) {
                $table->bigIncrements('id');
                $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->integer('bet_amount')->default(0);
                $table->timestamps();
                $table->unique(['wager_id', 'user_id'], 'wager_players_wager_id_user_id_unique');
            }
        });

        // Fix wager_players schema
        Schema::table('wager_players', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade')->change();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->change();
            $table->integer('bet_amount')->default(0)->change();
            $table->timestamps()->nullable()->change();
        });

        // Add unique constraint
        $constraintExists = DB::selectOne("
            SELECT 1 FROM information_schema.table_constraints
            WHERE constraint_name = 'wager_players_wager_id_user_id_unique'
            AND table_name = 'wager_players'
        ");
        if (! $constraintExists) {
            Schema::table('wager_players', function (Blueprint $table) {
                $table->unique(['wager_id', 'user_id'], 'wager_players_wager_id_user_id_unique');
            });
            \Log::info('Added unique constraint wager_players_wager_id_user_id_unique');
        }

        // Clean duplicates
        DB::statement("
            DELETE FROM wager_players
            WHERE id IN (
                SELECT id FROM (
                    SELECT id, ROW_NUMBER() OVER (PARTITION BY wager_id, user_id ORDER BY id) AS rnum
                    FROM wager_players
                ) t WHERE rnum > 1
            )
        ");
        \Log::info('Cleaned duplicate wager_players entries');

        // Fix wager_bets table
        Schema::create('wager_bets', function (Blueprint $table) {
            if (! Schema::hasTable('wager_bets')) {
                $table->bigIncrements('id');
                $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
                $table->foreignId('wager_choice_id')->constrained('wager_choices')->onDelete('cascade');
                $table->foreignId('wager_player_id')->constrained('wager_players')->onDelete('cascade');
                $table->integer('bet_amount');
                $table->integer('amount');
                $table->string('status')->default('pending');
                $table->integer('actual_payout')->nullable();
                $table->timestamps();
            }
        });

        Schema::table('wager_bets', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade')->change();
            $table->foreignId('wager_choice_id')->constrained('wager_choices')->onDelete('cascade')->change();
            $table->foreignId('wager_player_id')->constrained('wager_players')->onDelete('cascade')->change();
            $table->integer('bet_amount')->change();
            $table->integer('amount')->change();
            $table->string('status')->default('pending')->change();
            $table->integer('actual_payout')->nullable()->change();
            $table->timestamps()->nullable()->change();
        });

        // Clean invalid actual_payout
        DB::statement("
            UPDATE wager_bets
            SET actual_payout = NULL
            WHERE actual_payout IS NOT NULL
            AND actual_payout::text !~ '^[0-9]+$'
        ");
        \Log::info('Cleaned invalid actual_payout values in wager_bets');
    }

    public function down()
    {
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION READ WRITE');
        Schema::table('wager_players', function (Blueprint $table) {
            $constraintExists = DB::selectOne("
                SELECT 1 FROM information_schema.table_constraints
                WHERE constraint_name = 'wager_players_wager_id_user_id_unique'
                AND table_name = 'wager_players'
            ");
            if ($constraintExists) {
                $table->dropUnique('wager_players_wager_id_user_id_unique');
            }
        });
    }
}
