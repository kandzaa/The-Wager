<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            // Check if foreign key constraint exists
            $constraintExists = DB::select("SELECT COUNT(*) as count FROM information_schema.table_constraints WHERE table_name = 'wagers' AND constraint_name = 'wagers_winning_choice_id_foreign'");
            if ($constraintExists[0]->count == 0) {
                $table->foreign('winning_choice_id')->references('id')->on('wager_choices')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropForeign(['winning_choice_id']);
        });
    }
};
