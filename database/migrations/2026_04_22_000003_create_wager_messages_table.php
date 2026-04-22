<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS wager_messages (
                id          BIGSERIAL PRIMARY KEY,
                wager_id    BIGINT NOT NULL REFERENCES wagers(id) ON DELETE CASCADE,
                user_id     BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                message     VARCHAR(300) NOT NULL,
                created_at  TIMESTAMP NOT NULL DEFAULT NOW()
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS wager_messages');
    }
};
