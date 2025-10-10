<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wager;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Basic stats
        $stats = [
            'total_wagers'         => Wager::count(),
            'active_wagers'        => Wager::where('status', 'active')->count(),
            'completed_wagers'     => Wager::where('status', 'completed')->count(),
            'pending_wagers'       => Wager::where('status', 'pending')->count(),
            'total_pot'            => Wager::sum('pot'),
            'avg_pot'              => Wager::avg('pot') ?? 0,
            'total_users'          => User::count(),
            'active_users'         => User::where('last_seen', '>=', now()->subDays(30))->count(),
            'total_wagered'        => \App\Models\WagerPlayer::sum('amount'),
            'avg_wager_amount'     => \App\Models\WagerPlayer::avg('amount') ?? 0,
        ];

        // Wagers created over the last 30 days
        $wagersOverTime = Wager::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('SUM(pot) as total_amount')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Wagers by status with additional metrics
        $wagersByStatus = Wager::select(
            'status',
            DB::raw('count(*) as count'),
            DB::raw('SUM(pot) as total_pot'),
            DB::raw('AVG(pot) as avg_pot')
        )
            ->groupBy('status')
            ->get();

        // Top wagers by pot
        $topWagers = Wager::select(
            'id', 
            'title as name', 
            'pot as total_amount',
            'status',
            'ending_time',
            DB::raw('(SELECT COUNT(*) FROM wager_players WHERE wager_players.wager_id = wagers.id) as player_count')
        )
            ->withCount('players')
            ->orderBy('pot', 'desc')
            ->limit(10)
            ->get();
            
        // Recent wager activities with more details
        $recentActivity = \App\Models\WagerPlayer::with(['wager', 'user'])
            ->select(
                'wager_id', 
                'user_id', 
                'bet_amount as amount', 
                'status',
                'created_at'
            )
            ->with(['wager' => function($query) {
                $query->select('id', 'title as wager_name', 'pot', 'created_at');
            }])
            ->with(['user' => function($query) {
                $query->select('id', 'name as user_name', 'profile_photo_path');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $wager = $item->wager;
                $user = $item->user;
                
                return (object)[
                    'wager_id'    => $item->wager_id,
                    'wager_name'  => $wager->wager_name ?? 'Deleted Wager',
                    'wager_pot'   => $wager->pot ?? 0,
                    'user_id'     => $item->user_id,
                    'user_name'   => $user->user_name ?? 'Deleted User',
                    'user_avatar' => $user->profile_photo_path ?? null,
                    'amount'      => $item->amount,
                    'status'      => $item->status,
                    'created_at'  => $wager->created_at ?? now(),
                    'updated_at'  => null,
                ];
            });

        // Additional metrics
        $metrics = [
            'total_wagered_this_week' => \App\Models\WagerPlayer::where('created_at', '>=', now()->startOfWeek())
                ->sum('bet_amount'),
            'new_users_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'active_wagers_ending_soon' => Wager::where('status', 'active')
                ->whereBetween('ending_time', [now(), now()->addDays(7)])
                ->count(),
            'recent_payouts' => \App\Models\WagerPlayer::where('status', 'won')
                ->sum('actual_payout'),
        ];
        
        // Ensure we have some sample data if the database is empty
        if ($wagersOverTime->isEmpty()) {
            $wagersOverTime = collect([
                (object)['date' => now()->subDays(2)->format('Y-m-d'), 'count' => 5, 'total_amount' => 100],
                (object)['date' => now()->subDay()->format('Y-m-d'), 'count' => 10, 'total_amount' => 200],
                (object)['date' => now()->format('Y-m-d'), 'count' => 15, 'total_amount' => 300],
            ]);
        }
        
        if ($wagersByStatus->isEmpty()) {
            $wagersByStatus = collect([
                (object)['status' => 'active', 'count' => 10, 'total_pot' => 500, 'avg_pot' => 50],
                (object)['status' => 'pending', 'count' => 5, 'total_pot' => 250, 'avg_pot' => 50],
                (object)['status' => 'completed', 'count' => 15, 'total_pot' => 750, 'avg_pot' => 50],
            ]);
        }

        return view('Admin.Statistics.statistics', [
            'stats'          => $stats,
            'wagersOverTime' => $wagersOverTime,
            'wagersByStatus' => $wagersByStatus,
            'topWagers'      => $topWagers,
            'recentActivity' => $recentActivity,
            'metrics'        => $metrics,
        ]);
    }
}
