<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $withinTransaction = false; // <-- ADD THIS LINE

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // <-- This line is likely causing the abort
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('balance')->default(500);
            $table->string('role')->default('user')->nullable(false);
            $table->rememberToken();
            $table->timestamp('last_daily_claim_at')->nullable();
            $table->timestamps();
        });

        // The check constraint you added in the last step
        DB::statement("ALTER TABLE users ADD CONSTRAINT check_user_role CHECK (role IN ('user', 'admin'))");

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
