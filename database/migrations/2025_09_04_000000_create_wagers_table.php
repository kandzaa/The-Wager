<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->integer('max_players');
            $table->string('status')->default('public');
            $table->timestamp('starting_time')->useCurrent();
            $table->timestamp('ending_time');
            $table->integer('pot')->default(0);
            $table->timestamp('ended_at')->nullable();
            $table->foreignId('winning_choice_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wagers');
    }
};
