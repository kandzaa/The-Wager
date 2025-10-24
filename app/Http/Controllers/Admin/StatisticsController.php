<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wager;
use App\Models\WagerBet;

class StatisticsController extends Controller
{
    public function index()
    {
        \Log::info('StatisticsController@index called');

        try {
            // Basic stats with null checks
            $stats = [
                'total_wagers'     => Wager::count(),
                'active_wagers'    => Wager::where('status', 'active')->count(),
                'completed_wagers' => Wager::where('status', 'ended')->count(),
                'pending_wagers'   => Wager::where('status', 'pending')->count(),
                'total_pot'        => (float) (Wager::sum('pot') ?? 0),
                'avg_pot'          => (float) (Wager::avg('pot') ?? 0),
                'total_users'      => User::count(),
                'active_users'     => User::whereNotNull('email_verified_at')->count(),
                'total_wagered'    => (float) (WagerBet::sum('bet_amount') ?? 0),
                'avg_wager_amount' => (float) (WagerBet::avg('bet_amount') ?? 0),
            ];

            \Log::info('Stats calculated', ['stats' => $stats]);

            // Rest of your code...
        } catch (\Exception $e) {
            \Log::error('Error in StatisticsController: ' . $e->getMessage(), [
                'exception' => $e,
                'trace'     => $e->getTraceAsString(),
            ]);

            // Return a safe response with minimal data
            return view('Admin.Statistics.statistics', [
                'stats'          => [
                    'total_wagers'     => 0,
                    'active_wagers'    => 0,
                    'completed_wagers' => 0,
                    'pending_wagers'   => 0,
                    'total_pot'        => 0,
                    'avg_pot'          => 0,
                    'total_users'      => 0,
                    'active_users'     => 0,
                    'total_wagered'    => 0,
                    'avg_wager_amount' => 0,
                ],
                'wagersOverTime' => collect([
                    (object) ['date' => now()->subDays(2)->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                    (object) ['date' => now()->subDay()->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                    (object) ['date' => now()->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                ]),
                'wagersByStatus' => collect([
                    (object) ['status' => 'active', 'count' => 0, 'total_pot' => 0, 'avg_pot' => 0],
                    (object) ['status' => 'pending', 'count' => 0, 'total_pot' => 0, 'avg_pot' => 0],
                    (object) ['status' => 'ended', 'count' => 0, 'total_pot' => 0, 'avg_pot' => 0],
                ]),
                'topWagers'      => collect(),
                'recentActivity' => collect(),
                'metrics'        => [
                    'active_wagers_ending_soon' => 0,
                    'total_wagered_this_week'   => 0,
                ],
                'error'          => 'An error occurred while loading statistics. Please try again later.',
            ]);
        }
    }
}
