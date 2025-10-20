<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            // Ensure actual_payout is integer and nullable
            $table->integer('actual_payout')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('wager_bets', function (Blueprint $table) {
            $table->integer('actual_payout')->change();
        });
    }
};
