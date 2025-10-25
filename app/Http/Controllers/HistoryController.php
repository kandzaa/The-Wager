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
            // Get wagers where user participated (either as creator or player)
            $userWagers = Wager::where('status', 'ended')
                ->where(function ($query) {
                    $query->where('creator_id', Auth::id())
                        ->orWhereHas('players', function ($q) {
                            $q->where('user_id', Auth::id());
                        });
                })
                ->with(['creator'])
                ->withCount('players')
                ->orderBy('updated_at', 'desc')
                ->paginate(9, ['*'], 'user_wagers');

            // Get public ended wagers where user didn't participate
            $publicWagers = Wager::where('status', 'ended')
                ->where('privacy', 'public')
                ->where('creator_id', '!=', Auth::id())
                ->whereDoesntHave('players', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['creator'])
                ->withCount('players')
                ->orderBy('updated_at', 'desc')
                ->paginate(9, ['*'], 'public_wagers');

            Log::info('HISTORY INDEX', [
                'user_wagers_count'   => $userWagers->count(),
                'public_wagers_count' => $publicWagers->count(),
            ]);

            return view('history', [
                'userWagers'   => $userWagers,
                'publicWagers' => $publicWagers,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading history index', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('history', [
                'userWagers'   => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9),
                'publicWagers' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9),
                'error'        => 'Failed to load wager history: ' . $e->getMessage(),
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
        try {
            Log::info('HISTORY SHOW START', [
                'wager_id'     => $wager->id,
                'wager_status' => $wager->status,
            ]);

            // Ensure the wager is ended
            if ($wager->status !== 'ended') {
                Log::warning('Attempted to view non-ended wager history', [
                    'wager_id' => $wager->id,
                    'status'   => $wager->status,
                ]);
                return redirect()->route('wagers.index')
                    ->with('error', 'This wager has not ended yet.');
            }

            // Load basic relationships
            $wager->load(['creator', 'choices', 'winningChoice']);

            // Get all bets for this wager with player and user info
            $bets = DB::table('wager_bets')
                ->join('wager_players', 'wager_bets.wager_player_id', '=', 'wager_players.id')
                ->join('users', 'wager_players.user_id', '=', 'users.id')
                ->join('wager_choices', 'wager_bets.wager_choice_id', '=', 'wager_choices.id')
                ->where('wager_bets.wager_id', $wager->id)
                ->select(
                    'wager_bets.id as bet_id',
                    'wager_bets.bet_amount',
                    'wager_bets.payout',
                    'wager_bets.is_win',
                    'wager_bets.wager_choice_id',
                    'users.id as user_id',
                    'users.name as user_name',
                    'wager_choices.label as choice_label',
                    'wager_players.bet_amount as player_total_bet'
                )
                ->get();

            Log::info('HISTORY SHOW - Loaded bets', [
                'wager_id'   => $wager->id,
                'bets_count' => $bets->count(),
            ]);

            // Group by user and calculate results
            $userResults  = [];
            $totalPot     = 0;
            $winnersCount = 0;

            foreach ($bets as $bet) {
                $userId = $bet->user_id;

                if (! isset($userResults[$userId])) {
                    $userResults[$userId] = [
                        'user_id'   => $userId,
                        'user_name' => $bet->user_name,
                        'total_bet' => 0,
                        'payout'    => 0,
                        'profit'    => 0,
                        'status'    => 'lost',
                        'bets'      => [],
                    ];
                }

                $isWinner  = $bet->is_win === 1 || $bet->is_win === true;
                $betAmount = (float) $bet->bet_amount;
                $payout    = (float) ($bet->payout ?? 0);
                $profit    = $payout - $betAmount;

                $userResults[$userId]['bets'][] = [
                    'choice'    => $bet->choice_label,
                    'amount'    => $betAmount,
                    'is_winner' => $isWinner,
                    'payout'    => $payout,
                    'profit'    => $profit,
                ];

                $userResults[$userId]['total_bet'] += $betAmount;
                $userResults[$userId]['payout'] += $payout;
                $userResults[$userId]['profit'] += $profit;

                $totalPot += $betAmount;

                if ($isWinner) {
                    $userResults[$userId]['status'] = 'won';
                }
            }

            // Count winners (unique users who won)
            $winnersCount = collect($userResults)->where('status', 'won')->count();

            // Sort by payout (winners first)
            $userResults = collect($userResults)
                ->sortByDesc('payout')
                ->values();

            // Get choice distribution
            $choiceDistribution = DB::table('wager_choices')
                ->where('wager_id', $wager->id)
                ->select('id', 'label', 'total_bet')
                ->get()
                ->map(function ($choice) use ($totalPot) {
                    $amount = (float) ($choice->total_bet ?? 0);
                    return [
                        'id'         => $choice->id,
                        'label'      => $choice->label,
                        'total_bet'  => $amount,
                        'percentage' => $totalPot > 0 ? round(($amount / $totalPot) * 100, 2) : 0,
                    ];
                });

            $stats = [
                'total_players'       => count($userResults),
                'total_pot'           => $wager->pot ?? 0,
                'total_bets'          => $bets->count(),
                'winning_choice_id'   => $wager->winning_choice_id,
                'winners_count'       => $winnersCount,
                'choice_distribution' => $choiceDistribution,
            ];

            Log::info('HISTORY SHOW - Stats calculated', [
                'wager_id'      => $wager->id,
                'total_players' => $stats['total_players'],
                'total_pot'     => $stats['total_pot'],
                'winners_count' => $winnersCount,
            ]);

            return view('history.show', compact('wager', 'stats', 'userResults'));

        } catch (\Exception $e) {
            Log::error('ERROR IN HISTORY SHOW', [
                'wager_id' => $wager->id ?? 'unknown',
                'error'    => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()->route('history')
                ->with('error', 'Failed to load wager details: ' . $e->getMessage());
        }
    }
}
