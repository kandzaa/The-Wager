<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->integer('max_players');
            $table->integer('entry_fee');
            $table->enum('status', ['public', 'private'])->default('public');
            $table->json('players');
            $table->json('game_history');
            $table->timestamp('starting_time');
            $table->timestamp('ending_time');
            $table->integer('pot')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wagers');
    }
};
