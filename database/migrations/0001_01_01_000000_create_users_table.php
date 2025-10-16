<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique(); // Define unique at creation
                $table->string('password');
                $table->string('role')->default('user');
                $table->rememberToken();
                $table->timestamps();
            });

            // For SQLite, handle the constraint at the application level
            if (DB::getDriverName() === 'sqlite') {
                try {
                    DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS check_user_role');
                } catch (\Exception $e) {
                    // Ignore if constraint doesn't exist
                }
            } else {
                // Try to add role constraint, ignore if it already exists
                try {
                    DB::statement("ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('admin', 'user'))");
                } catch (\Exception $e) {
                    Log::warning('Could not add role constraint (may already exist): ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Migration failed for users table: ' . $e->getMessage());
            throw $e; // Re-throw to fail and report
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS chk_role');
        }
        Schema::dropIfExists('users');
    }
};
