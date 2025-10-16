<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if role column doesn't exist
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        // Try to add constraint, ignore if it already exists
        try {
            DB::statement("ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('admin', 'user'))");
        } catch (\Exception $e) {
            // Constraint likely already exists, log but don't fail migration
            Log::warning('Could not add role constraint (may already exist): ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS chk_role');
    }
};
