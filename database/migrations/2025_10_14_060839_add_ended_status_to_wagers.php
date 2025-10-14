<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        DB::statement('ALTER TABLE wagers DROP CONSTRAINT IF EXISTS wagers_status_check');

        DB::statement("ALTER TABLE wagers ADD CONSTRAINT wagers_status_check CHECK (status IN ('public', 'private', 'ended'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE wagers DROP CONSTRAINT IF EXISTS wagers_status_check');

        DB::statement("ALTER TABLE wagers ADD CONSTRAINT wagers_status_check CHECK (status IN ('public', 'private'))");

        DB::table('wagers')->where('status', 'ended')->update(['status' => 'private']);
    }
};
