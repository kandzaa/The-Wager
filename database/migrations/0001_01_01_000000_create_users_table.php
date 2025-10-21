<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('balance')->default(0);
            $table->timestamps();
        });

        // Insert test user
        DB::table('users')->insert([
            'id'         => 1,
            'balance'    => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Log::info('Inserted test user id=1');
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
