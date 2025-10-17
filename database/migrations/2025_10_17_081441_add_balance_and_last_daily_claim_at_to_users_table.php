<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceAndLastDailyClaimAtToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('balance')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_daily_claim_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'last_daily_claim_at']);
        });
    }
}
