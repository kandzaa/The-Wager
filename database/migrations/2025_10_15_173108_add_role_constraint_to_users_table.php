<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });
        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('admin', 'user'))");
    }

    public function down()
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS chk_role');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
