<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        // Check if check constraint exists
        $constraintExists = DB::select("SELECT COUNT(*) as count FROM information_schema.check_constraints WHERE constraint_name = 'wagers_status_check'");
        if ($constraintExists[0]->count == 0) {
            DB::statement("ALTER TABLE wagers ADD CONSTRAINT wagers_status_check CHECK (status IN ('public', 'private', 'completed'))");
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE wagers DROP CONSTRAINT IF EXISTS wagers_status_check");
    }
};
