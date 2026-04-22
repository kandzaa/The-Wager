<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('ALTER TABLE users ADD COLUMN IF NOT EXISTS banned_until TIMESTAMP NULL DEFAULT NULL');
        DB::unprepared('ALTER TABLE users ADD COLUMN IF NOT EXISTS ban_reason VARCHAR(500) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::unprepared('ALTER TABLE users DROP COLUMN IF EXISTS banned_until');
        DB::unprepared('ALTER TABLE users DROP COLUMN IF EXISTS ban_reason');
    }
};
