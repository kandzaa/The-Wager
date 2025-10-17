<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up()
    {
        // Check if column exists, create if not
        $hasColumn = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name='wager_players' AND column_name='bet_amount'");

        if (empty($hasColumn)) {
            DB::statement("ALTER TABLE wager_players ADD COLUMN bet_amount INTEGER DEFAULT 0 NOT NULL");
        }
    }

    public function down()
    {
        DB::statement("ALTER TABLE wager_players DROP COLUMN IF EXISTS bet_amount");
    }
};
