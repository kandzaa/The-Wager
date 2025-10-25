<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wager;
use App\Models\WagerBet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    public function index()
    {
        try {
            Log::info('=== STATISTICS DEBUG START ===');

            // Basic stats - WITH DEBUG
            $totalWagers      = Wager::count();
            $activeWagers     = Wager::where('status', 'active')->count();
            $completedWagers  = Wager::where('status', 'ended')->count();
            $pendingWagers    = Wager::where('status', 'pending')->count();
            $totalPot         = Wager::sum('pot');
            $avgPot           = Wager::avg('pot');
            $totalUsers       = User::count();
            $activeUsers      = User::whereNotNull('email_verified_at')->count();
            $totalWagered     = WagerBet::sum('bet_amount');
            $avgWagerAmount   = WagerBet::avg('bet_amount');
            $newUsersThisWeek = User::where('created_at', '>=', now()->startOfWeek())->count();

            Log::info('BASIC STATS', [
                'total_wagers'     => $totalWagers,
                'active_wagers'    => $activeWagers,
                'completed_wagers' => $completedWagers,
                'total_pot'        => $totalPot,
                'total_wagered'    => $totalWagered,
            ]);

            $stats = [
                'total_wagers'        => $totalWagers,
                'active_wagers'       => $activeWagers,
                'completed_wagers'    => $completedWagers,
                'pending_wagers'      => $pendingWagers,
                'total_pot'           => (float) ($totalPot ?? 0),
                'avg_pot'             => (float) ($avgPot ?? 0),
                'total_users'         => $totalUsers,
                'active_users'        => $activeUsers,
                'total_wagered'       => (float) ($totalWagered ?? 0),
                'avg_wager_amount'    => (float) ($avgWagerAmount ?? 0),
                'new_users_this_week' => $newUsersThisWeek,
            ];

            // Wagers over time (last 14 days) - WITH DEBUG
            $startDate = now()->subDays(13)->startOfDay();
            Log::info('QUERYING WAGERS FROM', ['start_date' => $startDate]);

            $wagersRaw = DB::table('wagers')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(COALESCE(pot, 0)) as total_amount')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            Log::info('WAGERS OVER TIME RAW', ['data' => $wagersRaw->toArray()]);

            // Fill in missing dates for the last 14 days
            $dates = collect(range(0, 13))->map(function ($days) {
                return now()->subDays($days)->format('Y-m-d');
            })->reverse()->values();

            $wagersOverTime = $dates->map(function ($date) use ($wagersRaw) {
                $record = $wagersRaw->firstWhere('date', $date);
                return (object) [
                    'date'         => $date,
                    'count'        => $record ? (int) $record->count : 0,
                    'total_amount' => $record ? (float) $record->total_amount : 0,
                ];
            });

            Log::info('WAGERS OVER TIME FILLED', ['count' => $wagersOverTime->count()]);

            // Wagers by status - WITH DEBUG
            $wagersByStatusRaw = DB::table('wagers')
                ->select(
                    'status',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(COALESCE(pot, 0)) as total_pot'),
                    DB::raw('AVG(COALESCE(pot, 0)) as avg_pot')
                )
                ->groupBy('status')
                ->get();

            Log::info('WAGERS BY STATUS', ['data' => $wagersByStatusRaw->toArray()]);

            $wagersByStatus = $wagersByStatusRaw;

            // Top wagers by pot - WITH DEBUG
            $topWagersRaw = DB::table('wagers')
                ->select(
                    'wagers.id',
                    'wagers.name',
                    'wagers.pot',
                    'wagers.status',
                    DB::raw('COUNT(DISTINCT wager_players.id) as player_count')
                )
                ->leftJoin('wager_players', 'wagers.id', '=', 'wager_players.wager_id')
                ->groupBy('wagers.id', 'wagers.name', 'wagers.pot', 'wagers.status')
                ->orderBy('wagers.pot', 'desc')
                ->limit(5)
                ->get();

            Log::info('TOP WAGERS RAW', ['data' => $topWagersRaw->toArray()]);

            $topWagers = $topWagersRaw->map(function ($wager) {
                return (object) [
                    'id'           => $wager->id,
                    'name'         => $wager->name,
                    'pot'          => (float) ($wager->pot ?? 0),
                    'total_amount' => (float) ($wager->pot ?? 0),
                    'player_count' => (int) $wager->player_count,
                    'status'       => $wager->status,
                ];
            });

            // Recent activity - WITH DEBUG
            $recentActivityRaw = DB::table('wager_bets')
                ->join('wager_players', 'wager_bets.wager_player_id', '=', 'wager_players.id')
                ->join('wagers', 'wager_bets.wager_id', '=', 'wagers.id')
                ->join('users', 'wager_players.user_id', '=', 'users.id')
                ->select(
                    'wager_bets.id as bet_id',
                    'wager_bets.wager_id',
                    'wagers.name as wager_name',
                    'wagers.pot as wager_pot',
                    'users.name as user_name',
                    'wager_bets.bet_amount as amount',
                    'wager_bets.is_win',
                    'wager_bets.created_at'
                )
                ->orderBy('wager_bets.created_at', 'desc')
                ->limit(10)
                ->get();

            Log::info('RECENT ACTIVITY RAW', ['count' => $recentActivityRaw->count(), 'data' => $recentActivityRaw->toArray()]);

            $recentActivity = $recentActivityRaw->map(function ($activity) {
                $status = 'pending';
                if ($activity->is_win === 1 || $activity->is_win === true) {
                    $status = 'won';
                } elseif ($activity->is_win === 0 || $activity->is_win === false) {
                    $status = 'lost';
                }

                return (object) [
                    'wager_id'   => $activity->wager_id,
                    'wager_name' => $activity->wager_name ?? 'Unknown Wager',
                    'wager_pot'  => (float) ($activity->wager_pot ?? 0),
                    'user_name'  => $activity->user_name ?? 'Unknown User',
                    'amount'     => (float) ($activity->amount ?? 0),
                    'status'     => $status,
                    'created_at' => \Carbon\Carbon::parse($activity->created_at),
                ];
            });

            Log::info('RECENT ACTIVITY PROCESSED', ['count' => $recentActivity->count()]);

            // Calculate recent payouts (last 7 days) - WITH DEBUG
            $recentPayoutsSum = DB::table('wager_bets')
                ->where('is_win', true)
                ->where('created_at', '>=', now()->subDays(7))
                ->sum('payout');

            Log::info('RECENT PAYOUTS', ['sum' => $recentPayoutsSum]);

            // Active wagers ending soon
            $activeWagersEndingSoon = DB::table('wagers')
                ->where('status', 'active')
                ->where('ending_time', '<=', now()->addDays(3))
                ->where('ending_time', '>', now())
                ->count();

            // Total wagered this week
            $totalWageredThisWeek = DB::table('wagers')
                ->where('created_at', '>=', now()->startOfWeek())
                ->sum('pot');

            Log::info('METRICS', [
                'active_ending_soon' => $activeWagersEndingSoon,
                'wagered_this_week'  => $totalWageredThisWeek,
                'recent_payouts'     => $recentPayoutsSum,
            ]);

            // Prepare view data
            $viewData = [
                'stats'          => $stats,
                'wagersOverTime' => $wagersOverTime,
                'wagersByStatus' => $wagersByStatus,
                'topWagers'      => $topWagers,
                'recentActivity' => $recentActivity,
                'recent_payouts' => collect(),
                'metrics'        => [
                    'active_wagers_ending_soon' => $activeWagersEndingSoon,
                    'total_wagered_this_week'   => (float) ($totalWageredThisWeek ?? 0),
                    'new_users_this_week'       => $stats['new_users_this_week'],
                    'recent_payouts'            => (float) ($recentPayoutsSum ?? 0),
                ],
                'error'          => null,
            ];

            Log::info('=== STATISTICS DEBUG END - SUCCESS ===');

            return view('Admin.Statistics.statistics', $viewData);

        } catch (\Exception $e) {
            Log::error('=== STATISTICS ERROR ===', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $e->getTraceAsString(),
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
                    'recent_payouts'            => 0,
                ],
                'error'          => 'An error occurred while loading statistics: ' . $e->getMessage(),
            ]);
        }
    }
}
