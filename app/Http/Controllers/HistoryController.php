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
     * Show the specified wager's history details.
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

        // Load relationships
        $wager->load([
            'creator',
            'players.user',
            'bets.wagerPlayer.user',
        ]);

        // Calculate stats
        $totalBets    = $wager->bets()->sum('bet_amount') ?? 0;
        $totalPlayers = $wager->players()->count();

        // Get winning bets if there's a winning choice
        $winningBets = collect();
        if ($wager->winning_choice_id) {
            $winningBets = $wager->bets()
                ->where('choice_id', $wager->winning_choice_id)
                ->with('wagerPlayer.user')
                ->get();
        }

        $stats = [
            'total_players'     => $totalPlayers,
            'total_pot'         => $wager->pot ?? 0,
            'total_bets'        => $totalBets,
            'winning_choice_id' => $wager->winning_choice_id,
            'winning_bets'      => $winningBets,
            'winners_count'     => $winningBets->count(),
        ];

        return view('history.show', [
            'wager' => $wager,
            'stats' => $stats,
        ]);
    }
}
