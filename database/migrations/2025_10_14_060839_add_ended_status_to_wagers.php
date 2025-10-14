<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        // For SQLite, we need to use raw SQL to modify the table
        if (DB::getDriverName() === 'sqlite') {
            // First, check if the table exists and has the status column
            $hasStatusColumn = DB::selectOne(
                "SELECT COUNT(*) as count FROM pragma_table_info('wagers') WHERE name = 'status'"
            )->count > 0;

            if ($hasStatusColumn) {
                // Create a new table with the updated schema
                DB::statement('CREATE TABLE wagers_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    description TEXT,
                    creator_id INTEGER NOT NULL,
                    max_players INTEGER NOT NULL,
                    status VARCHAR(255) NOT NULL DEFAULT "public" CHECK (status IN ("public", "private", "ended")),
                    starting_time DATETIME NOT NULL,
                    ending_time DATETIME NOT NULL,
                    pot INTEGER NOT NULL DEFAULT 0,
                    ended_at DATETIME,
                    winning_choice_id INTEGER,
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY (creator_id) REFERENCES users (id) ON DELETE CASCADE,
                    FOREIGN KEY (winning_choice_id) REFERENCES wager_choices (id) ON DELETE SET NULL
                )');

                // Copy data from old table to new table
                DB::statement('INSERT INTO wagers_new SELECT * FROM wagers');

                // Drop old table and rename new one
                DB::statement('DROP TABLE wagers');
                DB::statement('ALTER TABLE wagers_new RENAME TO wagers');
            }
        } else {
            // For other databases, use the schema builder
            DB::statement("ALTER TABLE wagers MODIFY COLUMN status ENUM('public', 'private', 'ended') DEFAULT 'public'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, we'll need to recreate the table with the old schema
        if (DB::getDriverName() === 'sqlite') {
            // Create a new table with the original schema
            DB::statement('CREATE TABLE wagers_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                creator_id INTEGER NOT NULL,
                max_players INTEGER NOT NULL,
                status VARCHAR(255) NOT NULL DEFAULT "public" CHECK (status IN ("public", "private")),
                starting_time DATETIME NOT NULL,
                ending_time DATETIME NOT NULL,
                pot INTEGER NOT NULL DEFAULT 0,
                ended_at DATETIME,
                winning_choice_id INTEGER,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (creator_id) REFERENCES users (id) ON DELETE CASCADE,
                FOREIGN KEY (winning_choice_id) REFERENCES wager_choices (id) ON DELETE SET NULL
            )');

            // Copy data from current table to old table (only rows with valid status)
            DB::statement('INSERT INTO wagers_old SELECT * FROM wagers WHERE status IN ("public", "private")');

            // Drop current table and rename old one
            DB::statement('DROP TABLE wagers');
            DB::statement('ALTER TABLE wagers_old RENAME TO wagers');
        } else {
            // For other databases, just modify the column
            DB::statement("ALTER TABLE wagers MODIFY COLUMN status ENUM('public', 'private') DEFAULT 'public'");
        }
    }
};
