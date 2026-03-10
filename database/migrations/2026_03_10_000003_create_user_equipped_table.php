<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS user_equipped (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                slot VARCHAR(255) NOT NULL,
                cosmetic_id BIGINT REFERENCES cosmetics(id) ON DELETE SET NULL,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                UNIQUE(user_id, slot)
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS user_equipped');
    }
};