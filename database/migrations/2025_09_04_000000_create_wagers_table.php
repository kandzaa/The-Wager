<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateWagersTable extends Migration
{
    public function up()
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id('wager_id');
            $table->integer('pot')->default(0);
            $table->string('status')->default('open');
            $table->timestamps();
        });
        Log::info('Created wagers table');
    }

    public function down()
    {
        Schema::dropIfExists('wagers');
        Log::info('Dropped wagers table');
    }
}
