<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function dailyBalance(): JsonResponse
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

        try {
            $user->balance += 10; // Add 10 to balance
            $user->last_daily_claim_at = now();
            $user->save();

            return response()->json([
                'message'             => 'Daily balance added.',
                'balance'             => $user->balance,
                'last_daily_claim_at' => $user->last_daily_claim_at->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to claim daily balance. Please try again.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function test_daily_balance_claim()
    {
        $user = User::factory()->create(['balance' => 0, 'last_daily_claim_at' => null]);
        $this->actingAs($user);
        $response = $this->postJson('/dailyBalance');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Daily balance added.', 'balance' => 10]);
        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'balance' => 10,
        ]);
    }
}
