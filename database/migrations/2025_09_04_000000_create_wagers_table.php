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
            $table->integer('max_players')->default(10);
            $table->enum('status', ['pending', 'active', 'ended'])->default('pending');
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->decimal('pot', 10, 2)->default(0);
            $table->timestamp('starting_time');
            $table->timestamp('ending_time');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('winning_choice_id')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('privacy');
            $table->index('ending_time');
            $table->index('winning_choice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wagers');
    }
};
