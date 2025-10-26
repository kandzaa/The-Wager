<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            // Add is_win column (used to mark winners/losers)
            if (! Schema::hasColumn('wager_bets', 'is_win')) {
                $table->boolean('is_win')->nullable()->after('amount');
            }

            // Add payout column (stores actual payout amount for winners)
            if (! Schema::hasColumn('wager_bets', 'payout')) {
                $table->integer('payout')->default(0)->after('is_win');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            if (Schema::hasColumn('wager_bets', 'is_win')) {
                $table->dropColumn('is_win');
            }
            if (Schema::hasColumn('wager_bets', 'payout')) {
                $table->dropColumn('payout');
            }
        });
    }
};
