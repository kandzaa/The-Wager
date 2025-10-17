<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wagers', function (Blueprint $table) {
            if (! Schema::hasColumn('wagers', 'privacy')) {
                $table->enum('privacy', ['public', 'private'])->default('public');
            }
        });
    }

    public function down()
    {
        Schema::table('wagers', function (Blueprint $table) {
            $table->dropColumn('privacy');
        });
    }
};
