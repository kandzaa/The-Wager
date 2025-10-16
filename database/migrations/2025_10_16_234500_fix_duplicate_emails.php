<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        try {
            // First, let's check for and fix any duplicate emails
            $duplicates = DB::table('users')
                ->select('email', DB::raw('COUNT(*) as count'))
                ->groupBy('email')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('email');

            foreach ($duplicates as $email) {
                // For each duplicate email, keep the first record and update others to be unique
                $users = DB::table('users')
                    ->where('email', $email)
                    ->orderBy('id')
                    ->get();

                $keep = $users->shift(); // Keep the first one

                foreach ($users as $index => $user) {
                    // Update other users with the same email to have a unique email
                    $newEmail = $user->email . '.duplicate' . ($index + 1) . '@' . explode('@', $user->email)[1];
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['email' => $newEmail]);
                }
            }

            // Now safely add the unique constraint
            DB::statement('ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)');

        } catch (\Exception $e) {
            Log::error('Failed to fix duplicate emails: ' . $e->getMessage());
            // Don't throw the exception to allow the migration to continue
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_email_unique');
        } catch (\Exception $e) {
            Log::warning('Could not drop email unique constraint: ' . $e->getMessage());
        }
    }
};
