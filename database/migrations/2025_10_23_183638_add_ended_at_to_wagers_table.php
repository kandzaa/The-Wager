<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->timestamp('ended_at')->nullable()->after('ending_time');
        });
    }

    public function down()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropColumn('ended_at');
        });
    }
};
