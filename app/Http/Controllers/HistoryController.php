<?php

namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
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
        // Get user's wagers
        $userWagers = Auth::user()->wagers()
            ->with(['choices', 'winningChoice', 'players'])
            ->where('status', 'ended')
            ->latest('ended_at')
            ->paginate(10);
            
        // Get public wagers
        $publicWagers = Wager::where('status', 'ended')
            ->where('creator_id', '!=', Auth::id())
            ->whereDoesntHave('players', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['choices', 'winningChoice', 'players'])
            ->latest('ended_at')
            ->paginate(10);

        return view('history', [
            'userWagers' => $userWagers,
            'publicWagers' => $publicWagers
        ]);
    }

    /**
     * Show the specified wager's history details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $wager = Wager::with([
            'choices',
            'winningChoice',
            'players' => function($query) {
                $query->with('user');
            },
            'bets' => function($query) {
                $query->with(['wagerPlayer.user', 'choice']);
            }
        ])->findOrFail($id);

        // Ensure the wager is ended
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.show', $wager);
        }

        // Calculate winnings and other stats
        $stats = [
            'total_players' => $wager->players->count(),
            'total_pot' => $wager->pot,
            'winning_choice' => $wager->winningChoice,
            'winning_bets' => $wager->bets->where('choice_id', $wager->winning_choice_id),
        ];

        return view('wagers.results', [
            'wager' => $wager,
            'stats' => $stats,
        ]);
    }
}
