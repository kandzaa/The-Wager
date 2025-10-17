<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_players');
            $table->enum('status', ['pending', 'active', 'ended'])->default('pending'); // Add if missing
            $table->string('privacy')->default('public');                               // ← ADD THIS LINE
            $table->timestamp('starting_time');
            $table->timestamp('ending_time');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wagers');
    }
};
