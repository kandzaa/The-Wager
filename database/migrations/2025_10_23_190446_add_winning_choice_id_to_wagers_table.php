<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->unsignedBigInteger('winning_choice_id')->nullable()->after('status');

            // Add foreign key constraint
            $table->foreign('winning_choice_id')
                ->references('id')
                ->on('wager_choices')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropForeign(['winning_choice_id']);
            $table->dropColumn('winning_choice_id');
        });
    }
};
