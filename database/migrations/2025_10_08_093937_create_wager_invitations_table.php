<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Set this to false to prevent PostgreSQL from wrapping DDL operations
     * in a single transaction, which helps prevent the 25P02 error if
     * a dependency is not immediately available.
     */
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('wager_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->references('id')->on('wagers')->onDelete('cascade');
            $table->foreignId('inviter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('invitee_id')->nullable()->references('id')->on('users')->onDelete('cascade');

            $table->string('email');
            $table->string('token', 64)->unique();
            $table->string('status')->default('pending');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['wager_id', 'email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wager_invitations');
    }
};
