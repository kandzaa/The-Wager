<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BalanceController extends Controller
{
    public function dailyBalance(): JsonResponse
    {
        $user = Auth::user();

        // Check cooldown
        if ($user->last_daily_claim_at) {
            $nextEligible = $user->last_daily_claim_at->addDay();
            if (now()->lt($nextEligible)) {
                return response()->json([
                    'message' => 'Already claimed today.',
                    'next_eligible_at' => $nextEligible->toIso8601String(),
                    'seconds_left' => now()->diffInSeconds($nextEligible, false),
                ], 429);
            }
        }

        try {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'balance' => DB::raw('balance + 10'),
                    'last_daily_claim_at' => now(),
                    'updated_at' => now(),
                ]);

            $user->refresh();

            return response()->json([
                'message' => 'Daily balance added.',
                'balance' => $user->balance,
                'last_daily_claim_at' => $user->last_daily_claim_at->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            Log::error('Daily balance claim failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to claim daily balance.',
            ], 500);
        }
    }
}
