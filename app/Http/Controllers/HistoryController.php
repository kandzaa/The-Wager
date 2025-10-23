<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of the user's wager history.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get wagers where user participated (either as creator or player)
        $userWagers = Wager::where('status', 'ended')
            ->where(function ($query) {
                $query->where('creator_id', Auth::id())
                    ->orWhereHas('players', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
            })
            ->with(['creator', 'players.user'])
            ->withCount('players')
            ->latest('ending_time')
            ->paginate(10);

        // Get public ended wagers where user didn't participate
        $publicWagers = Wager::where('status', 'ended')
            ->where('privacy', 'public')
            ->where('creator_id', '!=', Auth::id())
            ->whereDoesntHave('players', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['creator', 'players.user'])
            ->withCount('players')
            ->latest('ending_time')
            ->paginate(10);

        return view('history', [
            'userWagers'   => $userWagers,
            'publicWagers' => $publicWagers,
        ]);
    }

    /**
     * Show the specified wager's history details and results.
     *
     * @param  Wager  $wager
     * @return \Illuminate\View\View
     */
    public function show(Wager $wager)
    {
        // Ensure the wager is ended
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'This wager has not ended yet.');
        }

        // Load all necessary relationships safely
        $wager->load([
            'creator',
            'players' => function ($query) {
                $query->with(['user', 'bets']);
            },
        ]);

        // Calculate stats safely
        $totalBets    = 0;
        $totalPlayers = $wager->players->count();
        $winningBets  = collect();

        // Calculate total bets and find winners
        foreach ($wager->players as $player) {
            if ($player->bets) {
                $totalBets += $player->bets->sum('bet_amount');

                // Check if this player has winning bets
                if ($wager->winning_choice_id) {
                    $playerWinningBets = $player->bets->filter(function ($bet) use ($wager) {
                        return $bet->choice_id == $wager->winning_choice_id && $bet->is_win == true;
                    });

                    if ($playerWinningBets->isNotEmpty()) {
                        // Add player info to bets for display
                        foreach ($playerWinningBets as $bet) {
                            $bet->wagerPlayer = $player;
                            $winningBets->push($bet);
                        }
                    }
                }
            }
        }

        $stats = [
            'total_players'     => $totalPlayers,
            'total_pot'         => $wager->pot ?? 0,
            'total_bets'        => $totalBets,
            'winning_choice_id' => $wager->winning_choice_id,
            'winning_bets'      => $winningBets,
            'winners_count'     => $winningBets->count(),
        ];

        return view('history.show', compact('wager', 'stats'));
    }
}
