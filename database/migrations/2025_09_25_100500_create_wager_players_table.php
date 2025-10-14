<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        Schema::create('wager_players', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('wager_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->decimal('bet_amount')->nullable();
            $table->foreignId('choice_id')->nullable()->constrained('wager_choices')->onDelete('set null');

            $table->enum('status', ['pending', 'won', 'lost'])->default('pending');
            $table->decimal('potential_payout')->nullable();
            $table->decimal('actual_payout')->nullable();

            // Indexes for better query performance
            $table->index('wager_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('placed_at');

            $hasCreated = Schema::hasColumn('wager_players', 'created_at');
            $hasUpdated = Schema::hasColumn('wager_players', 'updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wager_players');
    }
};
