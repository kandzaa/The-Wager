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
            $table->integer('pot')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_players');
            $table->enum('status', ['pending', 'active', 'ended'])->default('pending');
            $table->string('privacy')->default('public');
            $table->timestamp('starting_time');
            $table->timestamp('ending_time');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('winning_choice_id')->nullable()->constrained('wager_choices')->onDelete('set null');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wagers');
    }
};
