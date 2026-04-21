<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('ALTER TABLE cosmetics DROP COLUMN IF EXISTS key');
    }

    public function down(): void
    {
        DB::unprepared('ALTER TABLE cosmetics ADD COLUMN key VARCHAR(255) NOT NULL DEFAULT \'\'');
    }
};
