<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wagers', function (Blueprint $table) {
            // Add column without foreign key constraint
            $table->unsignedBigInteger('winning_choice_id')->nullable()->after('status');

            // Add index for performance
            $table->index('winning_choice_id');
        });
    }

    public function down()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropIndex(['winning_choice_id']);
            $table->dropColumn('winning_choice_id');
        });
    }
};
