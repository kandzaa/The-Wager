<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        Schema::create('wager_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->constrained()->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('invitee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->string('status')->default('pending'); // pending, accepted, declined, expired
            $table->timestamp('expires_at');
            $table->timestamps();

            // Ensure we don't have duplicate pending invites for same email and wager
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
