<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public bool $withinTransaction = false;

    public function up(): void
    {
        Schema::create('user_equipped', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slot');
            $table->foreignId('cosmetic_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        DB::statement('CREATE UNIQUE INDEX user_equipped_user_slot_unique ON user_equipped (user_id, slot)');
    }

    public function down(): void
    {
        Schema::dropIfExists('user_equipped');
    }
};