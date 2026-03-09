<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('wagers', function (Blueprint $table) {
        $table->timestamp('ended_at')->nullable()->after('winning_choice_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropColumn('ended_at');
        });
    }
};
