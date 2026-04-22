<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS coinflip_games (
                id          BIGSERIAL PRIMARY KEY,
                user_id     BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                amount      INTEGER NOT NULL,
                pick        VARCHAR(10) NOT NULL,
                result      VARCHAR(10) NOT NULL,
                won         BOOLEAN NOT NULL,
                payout      INTEGER NOT NULL,
                created_at  TIMESTAMP NOT NULL DEFAULT NOW()
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS coinflip_games');
    }
};
