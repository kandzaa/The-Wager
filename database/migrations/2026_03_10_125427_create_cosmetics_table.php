<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cosmetics', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. 'frame_gold'
            $table->string('name');
            $table->string('type'); // frame, title, theme, charm
            $table->string('rarity'); // common, uncommon, rare, epic, legendary
            $table->integer('price');
            $table->json('meta')->nullable(); // color, preview, emoji, etc.
            $table->timestamps();
        });

        Schema::create('user_cosmetics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cosmetic_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'cosmetic_id']);
        });

        Schema::create('user_equipped', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slot'); // frame, title, theme, charm_1, charm_2, charm_3
            $table->foreignId('cosmetic_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_equipped');
        Schema::dropIfExists('user_cosmetics');
        Schema::dropIfExists('cosmetics');
    }
};