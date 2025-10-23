<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagersTable extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->string('privacy')->default('public');
            $table->integer('pot')->default(0);
            $table->integer('max_players')->nullable();
            $table->timestamp('starting_time')->nullable();
            $table->timestamp('ending_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wagers');
    }
}
