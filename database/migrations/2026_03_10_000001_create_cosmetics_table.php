<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS cosmetics (
                id BIGSERIAL PRIMARY KEY,
                key VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                rarity VARCHAR(255) NOT NULL,
                price INTEGER NOT NULL,
                meta TEXT,
                created_at TIMESTAMP,
                updated_at TIMESTAMP
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS cosmetics');
    }
};