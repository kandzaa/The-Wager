<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('balance')->default(20);
            $table->timestamp('last_daily_claim_at');
            $table->timestamps();
        });
        Log::info('Created users table');
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Log::info('Dropped users table');
    }
}
