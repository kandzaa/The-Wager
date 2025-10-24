<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wager;
use App\Models\WagerBet;
use App\Models\WagerPlayer;
use Illuminate\Support\Facades\DB;

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

            // Wagers over time (last 7 days)
            $wagersOverTime = Wager::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(pot), 0) as total_amount')
            )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Fill in missing dates
            $dates = collect(range(0, 6))->map(function ($days) {
                return now()->subDays($days)->format('Y-m-d');
            })->reverse();

            $wagersOverTime = $dates->map(function ($date) use ($wagersOverTime) {
                $record = $wagersOverTime->firstWhere('date', $date);
                return (object) [
                    'date'         => $date,
                    'count'        => $record->count ?? 0,
                    'total_amount' => $record->total_amount ?? 0,
                ];
            });

            // Wagers by status
            $wagersByStatus = Wager::select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(pot), 0) as total_pot'),
                DB::raw('COALESCE(AVG(pot), 0) as avg_pot')
            )
                ->groupBy('status')
                ->get();

            // Top wagers
            $topWagers = Wager::withCount('players')
                ->orderBy('pot', 'desc')
                ->take(5)
                ->get()
                ->map(function ($wager) {
                    return (object) [
                        'id'           => $wager->id,
                        'name'         => $wager->name,
                        'pot'          => $wager->pot,
                        'player_count' => $wager->players_count,
                        'status'       => $wager->status,
                    ];
                });

            // Recent activity
            $recentActivity = WagerPlayer::with(['wager', 'user'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($activity) {
                    return (object) [
                        'wager_id'   => $activity->wager_id,
                        'wager_name' => $activity->wager->name ?? 'Deleted Wager',
                        'user_name'  => $activity->user->name ?? 'Deleted User',
                        'amount'     => $activity->bet_amount,
                        'created_at' => $activity->created_at->diffForHumans(),
                    ];
                });

                                        // Recent payouts
            $recentPayouts = collect(); // You'll need to implement this based on your payout logic

            // Prepare view data
            $viewData = [
                'stats'          => $stats,
                'wagersOverTime' => $wagersOverTime,
                'wagersByStatus' => $wagersByStatus,
                'topWagers'      => $topWagers,
                'recentActivity' => $recentActivity,
                'recent_payouts' => $recentPayouts,
                'metrics'        => [
                    'active_wagers_ending_soon' => Wager::where('status', 'active')
                        ->where('ending_time', '<=', now()->addDays(3))
                        ->count(),
                    'total_wagered_this_week'   => (float) Wager::where('created_at', '>=', now()->startOfWeek())
                        ->sum('pot'),
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
                    'total_wagers'        => 0,
                    'active_wagers'       => 0,
                    'completed_wagers'    => 0,
                    'pending_wagers'      => 0,
                    'total_pot'           => 0,
                    'avg_pot'             => 0,
                    'total_users'         => 0,
                    'active_users'        => 0,
                    'total_wagered'       => 0,
                    'avg_wager_amount'    => 0,
                    'new_users_this_week' => 0,
                ],
                'wagersOverTime' => collect(),
                'wagersByStatus' => collect(),
                'topWagers'      => collect(),
                'recentActivity' => collect(),
                'recent_payouts' => collect(),
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
