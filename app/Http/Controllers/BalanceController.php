<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{

    public function dailyBalance()
    {
        $user = Auth::user();
        // Enforce 24-hour cooldown
        if ($user->last_daily_claim_at && now()->lt($user->last_daily_claim_at->copy()->addDay())) {
            $nextEligible = $user->last_daily_claim_at->copy()->addDay();
            $secondsLeft  = now()->diffInSeconds($nextEligible, false);
            return response()->json([
                'message'          => 'You have already claimed your daily balance.',
                'next_eligible_at' => $nextEligible->toIso8601String(),
                'seconds_left'     => $secondsLeft,
            ], 429);
        }

        $user->balance += 10;
        $user->last_daily_claim_at = now();
        $user->save();

        return response()->json([
            'message'             => 'Daily balance added.',
            'balance'             => $user->balance,
            'last_daily_claim_at' => $user->last_daily_claim_at->toIso8601String(),
        ]);
    }

}
