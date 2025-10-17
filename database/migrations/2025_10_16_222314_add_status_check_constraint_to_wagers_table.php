<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        DB::statement("ALTER TABLE wagers DROP CONSTRAINT IF EXISTS wagers_status_check");
        DB::statement("ALTER TABLE wagers ADD CONSTRAINT wagers_status_check CHECK (status IN ('pending', 'active', 'ended', 'cancelled'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE wagers DROP CONSTRAINT IF EXISTS wagers_status_check");
    }
};
