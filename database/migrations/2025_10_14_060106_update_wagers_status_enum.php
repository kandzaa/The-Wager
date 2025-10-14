<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop the existing constraint
        if (DB::getDriverName() === 'sqlite') {
            // SQLite specific way to drop and recreate the table
            Schema::table('wagers', function (Blueprint $table) {
                // Create a temporary table with the new schema
                $table->dropColumn('status');
            });

            Schema::table('wagers', function (Blueprint $table) {
                $table->enum('status', ['public', 'private', 'ended'])->default('public');
            });
        } else {
            // For other databases
            DB::statement("ALTER TABLE wagers MODIFY COLUMN status ENUM('public', 'private', 'ended') DEFAULT 'public'");
        }
    }

    public function down()
    {
        // Revert the changes if needed
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('wagers', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table('wagers', function (Blueprint $table) {
                $table->enum('status', ['public', 'private'])->default('public');
            });
        } else {
            DB::statement("ALTER TABLE wagers MODIFY COLUMN status ENUM('public', 'private') DEFAULT 'public'");
        }
    }
};
