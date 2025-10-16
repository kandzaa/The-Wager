<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we'll handle the constraint at the application level
        if (DB::getDriverName() === 'sqlite') {
            // Ensure the column exists and has a default value
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->change();
            });
            
            // Remove the existing constraint if it exists (will work in SQLite)
            try {
                DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS check_user_role');
            } catch (\Exception $e) {
                // Ignore if constraint doesn't exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything in the down method for SQLite
        // The original migration will handle the rollback for other databases
    }
};
