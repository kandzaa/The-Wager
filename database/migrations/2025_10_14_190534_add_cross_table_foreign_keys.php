<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Run each table modification in its own transaction
        Schema::table('wagers', function (Blueprint $table) use (&$hasErrors) {
            try {
                // Check if the constraint already exists
                $constraintExists = DB::select("SELECT COUNT(*) as count FROM information_schema.table_constraints WHERE table_name = 'wagers' AND constraint_name = 'wagers_winning_choice_id_foreign'");
                if ($constraintExists[0]->count == 0) {
                    $table->foreign('winning_choice_id')->references('id')->on('wager_choices')->onDelete('set null');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to add winning_choice_id foreign key: ' . $e->getMessage());
                $hasErrors = true;
            }
        });

        // Add other foreign key constraints for other tables if needed
        // Example: Schema::table('other_table', function (Blueprint $table) { ... });
    }

    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropForeign(['winning_choice_id']);
        });
    }
};
