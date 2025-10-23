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
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('balance')->default(0);
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
