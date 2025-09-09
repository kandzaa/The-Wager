<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('wagers', 'entry_fee')) {
            Schema::table('wagers', function (Blueprint $table) {
                $table->dropColumn('entry_fee');
            });
        }
    }

    public function down(): void
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->integer('entry_fee')->default(0);
        });
    }
};
