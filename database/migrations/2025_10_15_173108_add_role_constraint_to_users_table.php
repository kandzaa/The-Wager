<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, we'll handle the role validation in the model
        // This migration will just ensure the column exists with a default value
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });

        // For other databases, we can add the constraint
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users ADD CONSTRAINT check_user_role CHECK (role IN ('user', 'admin'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS check_user_role');
        }
    }
};
