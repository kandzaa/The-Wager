<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_friends', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('friend_id');
            $table->string('status')->default('pending');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('user_friends');
    }
};
