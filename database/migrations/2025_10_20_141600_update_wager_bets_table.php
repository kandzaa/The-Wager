<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            // Add actual_payout column as integer to match model expectations
            $table->integer('actual_payout')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            $table->dropColumn('actual_payout');
        });
    }
};
