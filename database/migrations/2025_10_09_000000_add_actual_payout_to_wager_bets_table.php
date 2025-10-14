<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            $table->decimal('actual_payout', 10, 2)->default(0)->after('bet_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            $table->dropColumn('actual_payout');
        });
    }
};
