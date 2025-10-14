<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

            $table->string('status')->default('pending');

            $table->decimal('potential_payout')->nullable();
            $table->decimal('actual_payout')->nullable();

            $table->index('wager_id');
            $table->index('user_id');
            $table->index('status');
            $table->timestamp('placed_at')->nullable();

            $table->timestamps();
        });
        DB::statement("ALTER TABLE wager_players ADD CONSTRAINT wager_players_status_check
            CHECK (status IN ('pending', 'won', 'lost'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('wager_players');
    }
};
