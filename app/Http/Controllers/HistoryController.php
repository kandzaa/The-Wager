<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    /**
     * Display a listing of the user's wager history.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $userId = Auth::id();

            // User's wagers (participated in)
            $userWagers = Wager::where('status', 'ended')
                ->where(function ($q) use ($userId) {
                    $q->where('creator_id', $userId)
                      ->orWhereHas('players', fn($q) => $q->where('user_id', $userId));
                })
                ->with('creator')
                ->withCount('players')
                ->latest('updated_at')
                ->paginate(9, ['*'], 'user_wagers');

            // Public wagers (not participated in)
            $publicWagers = Wager::where('status', 'ended')
                ->where('privacy', 'public')
                ->where('creator_id', '!=', $userId)
                ->whereDoesntHave('players', fn($q) => $q->where('user_id', $userId))
                ->with('creator')
                ->withCount('players')
                ->latest('updated_at')
                ->paginate(9, ['*'], 'public_wagers');

            return view('history', compact('userWagers', 'publicWagers'));
        } catch (\Exception $e) {
            Log::error('History index error', ['error' => $e->getMessage()]);
            return view('history', [
                'userWagers' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9),
                'publicWagers' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9),
            ]);
        }
    }

    /**
     * Show the specified wager's history details and results.
     *
     * @param  Wager  $wager
     * @return \Illuminate\View\View
     */
    public function show(Wager $wager)
    {
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.index')->with('error', 'Wager not ended yet.');
        }

        try {
            $wager->load(['creator', 'choices', 'winningChoice']);

            // Get bets with user info
            $bets = DB::table('wager_bets')
                ->join('wager_players', 'wager_bets.wager_player_id', '=', 'wager_players.id')
                ->join('users', 'wager_players.user_id', '=', 'users.id')
                ->join('wager_choices', 'wager_bets.wager_choice_id', '=', 'wager_choices.id')
                ->where('wager_bets.wager_id', $wager->id)
                ->select(
                    'wager_bets.bet_amount',
                    'wager_bets.payout',
                    'wager_bets.is_win',
                    'users.id as user_id',
                    'users.name as user_name',
                    'wager_choices.label as choice_label'
                )
                ->get();

            // Calculate user results
            $userResults = [];
            $totalPot = 0;

            foreach ($bets as $bet) {
                $userId = $bet->user_id;
                if (!isset($userResults[$userId])) {
                    $userResults[$userId] = [
                        'user_id' => $userId,
                        'user_name' => $bet->user_name,
                        'total_bet' => 0,
                        'payout' => 0,
                        'profit' => 0,
                        'status' => 'lost',
                        'bets' => [],
                    ];
                }

                $isWinner = (bool)$bet->is_win;
                $amount = (float)$bet->bet_amount;
                $payout = (float)($bet->payout ?? 0);

                $userResults[$userId]['bets'][] = [
                    'choice' => $bet->choice_label,
                    'amount' => $amount,
                    'is_winner' => $isWinner,
                    'payout' => $payout,
                    'profit' => $payout - $amount,
                ];

                $userResults[$userId]['total_bet'] += $amount;
                $userResults[$userId]['payout'] += $payout;
                $userResults[$userId]['profit'] += $payout - $amount;
                $totalPot += $amount;

                if ($isWinner) {
                    $userResults[$userId]['status'] = 'won';
                }
            }

            $userResults = collect($userResults)->sortByDesc('payout')->values();

            // Choice distribution
            $choiceDistribution = DB::table('wager_choices')
                ->where('wager_id', $wager->id)
                ->get(['id', 'label', 'total_bet'])
                ->map(fn($c) => [
                    'id' => $c->id,
                    'label' => $c->label,
                    'total_bet' => (float)($c->total_bet ?? 0),
                    'percentage' => $totalPot > 0 ? round(((float)($c->total_bet ?? 0) / $totalPot) * 100, 2) : 0,
                ]);

            $stats = [
                'total_players' => count($userResults),
                'total_pot' => $wager->pot ?? 0,
                'total_bets' => $bets->count(),
                'winning_choice_id' => $wager->winning_choice_id,
                'winners_count' => collect($userResults)->where('status', 'won')->count(),
                'choice_distribution' => $choiceDistribution,
            ];

            return view('history.show', compact('wager', 'stats', 'userResults'));
        } catch (\Exception $e) {
            Log::error('History show error', ['wager_id' => $wager->id, 'error' => $e->getMessage()]);
            return redirect()->route('history')->with('error', 'Failed to load wager details.');
        }
    }
}
