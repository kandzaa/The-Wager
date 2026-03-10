<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public bool $withinTransaction = false;

    public function up(): void
    {
        Schema::create('cosmetics', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->string('type');
            $table->string('rarity');
            $table->integer('price');
            $table->jsonb('meta')->nullable();
            $table->timestamps();
        });

        DB::statement('CREATE UNIQUE INDEX cosmetics_key_unique ON cosmetics (key)');
    }

    public function down(): void
    {
        Schema::dropIfExists('cosmetics');
    }
};