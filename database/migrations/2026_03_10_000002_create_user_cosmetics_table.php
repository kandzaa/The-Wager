<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public bool $withinTransaction = false;

    public function up(): void
    {
        Schema::create('user_cosmetics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cosmetic_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        DB::statement('CREATE UNIQUE INDEX user_cosmetics_user_cosmetic_unique ON user_cosmetics (user_id, cosmetic_id)');
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cosmetics');
    }
};