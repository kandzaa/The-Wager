<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('wager_bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wager_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wager_choice_id')->constrained('wager_choices')->onDelete('cascade');
            $table->integer('amount')->default(0); // Assuming 'bet_amount' was meant, renamed to 'amount' for clarity
            $table->decimal('actual_payout', 10, 2)->default(0)->after('amount');
            $table->timestamps();
        });

        Schema::table('wager_bets', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wager_bets');
    }
};
