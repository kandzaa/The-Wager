<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_equipped', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('slot');
            $table->unsignedBigInteger('cosmetic_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('cosmetic_id')->references('id')->on('cosmetics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_equipped');
    }
};