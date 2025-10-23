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
            $table->foreignId('wager_id')->constrained('wagers')->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('invitee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->string('status')->default('pending');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['wager_id', 'email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wager_invitations');
    }
};
