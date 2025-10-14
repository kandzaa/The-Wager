<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('wager_choices', function (Blueprint $table) {
            $table->foreign('wager_id')
                ->references('id')
                ->on('wagers')
                ->onDelete('cascade');
        });

        Schema::table('wagers', function (Blueprint $table) {
            $table->foreign('winning_choice_id')
                ->references('id')
                ->on('wager_choices')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('wager_choices', function (Blueprint $table) {
            $table->dropForeign(['wager_id']);
        });

        Schema::table('wagers', function (Blueprint $table) {
            $table->dropForeign(['winning_choice_id']);
        });
    }
};
