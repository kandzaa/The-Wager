<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagerChoicesTable extends Migration
{
    public function up()
    {
        // Ensure wagers table exists first
        if (! Schema::hasTable('wagers')) {
            Schema::create('wagers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('pot')->default(0);
                $table->string('status')->default('open');
                $table->timestamps();
            });
            Log::info('Created wagers table as dependency');
        }

        Schema::create('wager_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wager_id');
            $table->integer('total_bet')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Add foreign key in a separate step to avoid transaction abort
        Schema::table('wager_choices', function (Blueprint $table) {
            $table->foreign('wager_id')->references('id')->on('wagers')->onDelete('cascade')->change();
        });
        Log::info('Created wager_choices table with foreign key to wagers');
    }

    public function down()
    {
        Schema::dropIfExists('wager_choices');
        Schema::dropIfExists('wagers'); // Clean up if created here
        Log::info('Dropped wager_choices and wagers tables');
    }
}
