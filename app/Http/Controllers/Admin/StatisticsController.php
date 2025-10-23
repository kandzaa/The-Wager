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
        // Basic stats
        $stats = [
            'total_wagers'     => Wager::count(),
            'active_wagers'    => Wager::where('status', 'active')->count(),
            'completed_wagers' => Wager::where('status', 'ended')->count(),
            'pending_wagers'   => Wager::where('status', 'pending')->count(),
            'total_pot'        => Wager::sum('pot') ?? 0,
            'avg_pot'          => Wager::avg('pot') ?? 0,
            'total_users'      => User::count(),
            'active_users'     => User::whereNotNull('email_verified_at')->count(),
            'total_wagered'    => WagerBet::sum('bet_amount') ?? 0,
            'avg_wager_amount' => WagerBet::avg('bet_amount') ?? 0,
        ];

        // Wagers created over the last 30 days
        $wagersOverTime = Wager::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('COALESCE(SUM(pot), 0) as total_amount')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with 0
        if ($wagersOverTime->isNotEmpty()) {
            $filledData = [];
            for ($i = 29; $i >= 0; $i--) {
                $date         = now()->subDays($i)->format('Y-m-d');
                $found        = $wagersOverTime->firstWhere('date', $date);
                $filledData[] = (object) [
                    'date'         => $date,
                    'count'        => $found ? $found->count : 0,
                    'total_amount' => $found ? $found->total_amount : 0,
                ];
            }
            $wagersOverTime = collect($filledData);
        }

        // Wagers by status with additional metrics
        $wagersByStatus = Wager::select(
            'status',
            DB::raw('count(*) as count'),
            DB::raw('COALESCE(SUM(pot), 0) as total_pot'),
            DB::raw('COALESCE(AVG(pot), 0) as avg_pot')
        )
            ->groupBy('status')
            ->get();

        // Top wagers by pot
        $topWagers = Wager::select('wagers.*')
            ->selectRaw('COALESCE(wagers.pot, 0) as total_amount')
            ->selectRaw('(SELECT COUNT(*) FROM wager_players WHERE wager_players.wager_id = wagers.id) as player_count')
            ->orderBy('pot', 'desc')
            ->limit(10)
            ->get();

        // Recent wager activities
        $recentActivity = WagerPlayer::with(['wager', 'user'])
            ->select('wager_players.*')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                // Get total bet amount for this player
                $betAmount = WagerBet::where('wager_player_id', $item->id)->sum('bet_amount') ?? 0;

                // Determine status
                $status = 'pending';
                if ($item->wager && $item->wager->status === 'ended') {
                    $wonBet = WagerBet::where('wager_player_id', $item->id)
                        ->where('is_win', true)
                        ->exists();
                    $status = $wonBet ? 'won' : 'lost';
                }

                return (object) [
                    'wager_id'    => $item->wager_id,
                    'wager_name'  => $item->wager->name ?? 'Deleted Wager',
                    'wager_pot'   => $item->wager->pot ?? 0,
                    'user_id'     => $item->user_id,
                    'user_name'   => $item->user->name ?? 'Deleted User',
                    'user_avatar' => null,
                    'amount'      => $betAmount,
                    'status'      => $status,
                    'created_at'  => $item->created_at,
                ];
            });

        // Additional metrics
        $metrics = [
            'total_wagered_this_week'   => WagerBet::where('created_at', '>=', now()->startOfWeek())
                ->sum('bet_amount') ?? 0,
            'new_users_this_week'       => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'active_wagers_ending_soon' => Wager::where('status', 'active')
                ->whereBetween('ending_time', [now(), now()->addDays(7)])
                ->count(),
            'recent_payouts'            => WagerBet::where('is_win', true)
                ->where('updated_at', '>=', now()->startOfWeek())
                ->sum('payout') ?? 0,
        ];

        // Ensure we have some sample data if the database is empty
        if ($wagersOverTime->isEmpty()) {
            $wagersOverTime = collect([
                (object) ['date' => now()->subDays(2)->format('Y-m-d'), 'count' => 5, 'total_amount' => 100],
                (object) ['date' => now()->subDay()->format('Y-m-d'), 'count' => 10, 'total_amount' => 200],
                (object) ['date' => now()->format('Y-m-d'), 'count' => 15, 'total_amount' => 300],
            ]);
        }

        if ($wagersByStatus->isEmpty()) {
            $wagersByStatus = collect([
                (object) ['status' => 'active', 'count' => 10, 'total_pot' => 500, 'avg_pot' => 50],
                (object) ['status' => 'pending', 'count' => 5, 'total_pot' => 250, 'avg_pot' => 50],
                (object) ['status' => 'ended', 'count' => 15, 'total_pot' => 750, 'avg_pot' => 50],
            ]);
        }

        if ($topWagers->isEmpty()) {
            $topWagers = collect();
        }

        if ($recentActivity->isEmpty()) {
            $recentActivity = collect();
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
