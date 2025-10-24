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
        try {
            // Basic stats
            $stats = [
                'total_wagers'        => Wager::count(),
                'active_wagers'       => Wager::where('status', 'active')->count(),
                'completed_wagers'    => Wager::where('status', 'ended')->count(),
                'pending_wagers'      => Wager::where('status', 'pending')->count(),
                'total_pot'           => (float) Wager::sum('pot'),
                'avg_pot'             => (float) Wager::avg('pot'),
                'total_users'         => User::count(),
                'active_users'        => User::whereNotNull('email_verified_at')->count(),
                'total_wagered'       => (float) WagerBet::sum('bet_amount'),
                'avg_wager_amount'    => (float) WagerBet::avg('bet_amount'),
                'new_users_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            ];

            // Prepare view data
            $viewData = [
                'stats'          => $stats,
                'wagersOverTime' => collect([
                    (object) ['date' => now()->subDays(2)->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                    (object) ['date' => now()->subDay()->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                    (object) ['date' => now()->format('Y-m-d'), 'count' => 0, 'total_amount' => 0],
                ]),
                'wagersByStatus' => collect([
                    (object) ['status' => 'active', 'count' => $stats['active_wagers'], 'total_pot' => 0, 'avg_pot' => 0],
                    (object) ['status' => 'pending', 'count' => $stats['pending_wagers'], 'total_pot' => 0, 'avg_pot' => 0],
                    (object) ['status' => 'ended', 'count' => $stats['completed_wagers'], 'total_pot' => 0, 'avg_pot' => 0],
                ]),
                'topWagers'      => collect(),
                'recentActivity' => collect(),
                'metrics'        => [
                    'active_wagers_ending_soon' => 0,
                    'total_wagered_this_week'   => 0,
                    'new_users_this_week'       => $stats['new_users_this_week'],
                ],
                'error'          => null,
            ];

            return view('Admin.Statistics.statistics', $viewData);

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
                ],
                'wagersOverTime' => collect(),
                'wagersByStatus' => collect(),
                'topWagers'      => collect(),
                'recentActivity' => collect(),
                'metrics'        => [
                    'active_wagers_ending_soon' => 0,
                    'total_wagered_this_week'   => 0,
                    'new_users_this_week'       => 0,
                ],
                'error'          => 'An error occurred while loading statistics. Please try again later.',
            ]);
        }
    }
}
