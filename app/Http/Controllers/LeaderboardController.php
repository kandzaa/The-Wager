<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Top by total coins won (sum of payouts)
        $byWinnings = DB::table('users as u')
            ->join('wager_players as p', 'p.user_id', '=', 'u.id')
            ->join('wager_bets as b', 'b.wager_player_id', '=', 'p.id')
            ->select('u.id', 'u.name', DB::raw('SUM(b.payout) as total_won'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_won')
            ->limit(10)
            ->get();

        // Top by win rate (min 3 bets to qualify)
        $byWinRate = DB::table('users as u')
            ->join('wager_players as p', 'p.user_id', '=', 'u.id')
            ->join('wager_bets as b', 'b.wager_player_id', '=', 'p.id')
            ->select(
                'u.id',
                'u.name',
                DB::raw('COUNT(b.id) as total_bets'),
                DB::raw('SUM(CASE WHEN b.is_win = true THEN 1 ELSE 0 END) as wins'),
                DB::raw('ROUND(SUM(CASE WHEN b.is_win = true THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(b.id), 0) * 100, 1) as win_rate')
            )
            ->groupBy('u.id', 'u.name')
            ->having(DB::raw('COUNT(b.id)'), '>=', 3)
            ->orderByDesc('win_rate')
            ->limit(10)
            ->get();

        // Top by total wagers joined
        $byWagers = DB::table('users as u')
            ->join('wager_players as p', 'p.user_id', '=', 'u.id')
            ->select('u.id', 'u.name', DB::raw('COUNT(p.id) as total_wagers'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_wagers')
            ->limit(10)
            ->get();

        // Top by current balance
        $byBalance = DB::table('users')
            ->select('id', 'name', 'balance')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();

        return view('leaderboard', compact('byWinnings', 'byWinRate', 'byWagers', 'byBalance'));
    }
}
