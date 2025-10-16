<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            // Use DB::raw for PostgreSQL-compatible default
            $table->timestamp('starting_time')->default(\DB::raw('CURRENT_TIMESTAMP'))->change();
            // Add foreign key constraint for winning_choice_id if not already present
            $table->foreign('winning_choice_id')->references('id')->on('wager_choices')->onDelete('set null')->change();
        });
    }

    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->timestamp('starting_time')->default(null)->change();
            $table->dropForeign(['winning_choice_id']);
        });
    }
};
