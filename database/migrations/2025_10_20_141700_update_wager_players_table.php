<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            // Add choice_id if needed (optional)
            $table->foreignId('choice_id')->nullable()->constrained('wager_choices')->onDelete('set null')->after('user_id');
            // Add potential_payout and actual_payout if needed (optional)
            $table->integer('potential_payout')->nullable()->after('bet_amount');
            $table->integer('actual_payout')->nullable()->after('potential_payout');
        });
    }

    public function down()
    {
        Schema::table('wager_players', function (Blueprint $table) {
            $table->dropColumn(['choice_id', 'potential_payout', 'actual_payout']);
        });
    }
};
