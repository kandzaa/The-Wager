<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        // Disable transaction handling for this migration
        DB::statement('SET session_replication_role = replica;');

        // Create the table with raw SQL
        DB::statement('
        CREATE TABLE wager_choices (
            id BIGSERIAL PRIMARY KEY,
            wager_id BIGINT NOT NULL,
            total_bet INTEGER NOT NULL DEFAULT 0,
            label VARCHAR(255) NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            CONSTRAINT wager_choices_wager_id_foreign
            FOREIGN KEY (wager_id)
            REFERENCES wagers (id)
            ON DELETE CASCADE
        );
    ');

        // Re-enable replication
        DB::statement('SET session_replication_role = DEFAULT;');
    }

    public function down()
    {
        // Disable triggers temporarily
        DB::statement('SET session_replication_role = replica;');
        Schema::dropIfExists('wager_choices');
        DB::statement('SET session_replication_role = DEFAULT;');
    }
}
