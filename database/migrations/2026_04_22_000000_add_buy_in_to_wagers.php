<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('ALTER TABLE wagers ADD COLUMN IF NOT EXISTS buy_in INTEGER NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::unprepared('ALTER TABLE wagers DROP COLUMN IF EXISTS buy_in');
    }
};
