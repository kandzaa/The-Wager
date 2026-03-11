<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS user_cosmetics (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                cosmetic_id BIGINT NOT NULL REFERENCES cosmetics(id) ON DELETE CASCADE,
                created_at TIMESTAMP,
                updated_at TIMESTAMP
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS user_cosmetics');
    }
};