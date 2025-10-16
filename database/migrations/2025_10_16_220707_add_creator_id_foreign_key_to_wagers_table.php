<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade')->change();
        });
    }

    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
        });
    }
};
