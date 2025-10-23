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
            $table->string('status', 20)->default('pending');
            $table->string('privacy', 20)->default('public');
            $table->decimal('pot', 10, 2)->default(0);
            $table->timestamp('starting_time');
            $table->timestamp('ending_time');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('winning_choice_id')->nullable();
            $table->timestamps();

            // Add foreign key constraint inline
            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('status');
            $table->index('privacy');
            $table->index('ending_time');
            $table->index('winning_choice_id');
            $table->index('creator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wagers');
    }
};
