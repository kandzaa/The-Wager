<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        // Ensure wagers table exists (should already be created by 2025_09_04)
        if (! Schema::hasTable('wagers')) {
            throw new \Exception('wagers table not found, migration order may be incorrect');
        }

        Schema::create('wager_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wager_id');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Add foreign key in a separate step
        Schema::table('wager_choices', function (Blueprint $table) {
            $table->foreign('wager_id')->references('id')->on('wagers')->onDelete('cascade')->change();
        });
        Log::info('Created wager_choices table with foreign key to wagers');
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
        Log::info('Dropped wager_choices table');
    }
}
