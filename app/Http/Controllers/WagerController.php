<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use App\Models\WagerChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WagerController extends Controller
{
    public function index()
    {
        $wagers = Wager::with('choices')->latest()->get();
        return view('wagers.lobby', compact('wagers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'max_players' => 'required|integer|min:2|max:100',
            'ending_time' => 'required|date|after:' . now()->addHour()->format('Y-m-d H:i'),
            'choices'     => 'required|array|min:2|max:10',
            'choices.*'   => 'required|string|max:255',
            'status'      => 'required|in:public,private',
        ]);

        $wagerData = [
            'name'          => $validated['name'],
            'description'   => $request->description ?? null,
            'creator_id'    => Auth::id(),
            'max_players'   => $validated['max_players'],
            'status'        => $validated['status'],
            'starting_time' => now(),
            'ending_time'   => $validated['ending_time'],
        ];

        $wager = Wager::create($wagerData);

        foreach ($validated['choices'] as $choice) {
            $wager->choices()->create([
                'label'     => trim($choice),
                'total_bet' => 0,
            ]);
        }

        return redirect()->route('wagers');
    }

    public function show(Wager $wager)
    {
        $wager->load([
            'choices' => function ($query) {
                $query->orderBy('id');
            },
            'players' => function ($query) {
                $query->with('user');
            },
        ]);

        // Eager load the authenticated user's player data
        $userBet  = null;
        $isJoined = false;

        if (auth()->check()) {
            $userBet  = $wager->players->firstWhere('user_id', auth()->id());
            $isJoined = $userBet !== null;
        }

        $results = [
            'winners' => collect(),
            'losers'  => collect(),
        ];

        if ($wager->status === 'ended') {
            // Collect all bets for this wager with users
            $bets = \App\Models\WagerBet::whereHas('wagerPlayer', function ($q) use ($wager) {
                $q->where('wager_id', $wager->id);
            })->with('wagerPlayer.user')->get();

            $byUser = [];
            foreach ($bets as $bet) {
                $uid = $bet->wagerPlayer->user_id;
                if (! isset($byUser[$uid])) {
                    $byUser[$uid] = [
                        'user'         => $bet->wagerPlayer->user,
                        'total_bet'    => 0,
                        'total_payout' => 0,
                    ];
                }
                $byUser[$uid]['total_bet'] += (float) $bet->bet_amount;
                $byUser[$uid]['total_payout'] += (float) $bet->actual_payout;
            }

            $winners = [];
            $losers  = [];
            foreach ($byUser as $uid => $row) {
                $net   = $row['total_payout'] - $row['total_bet'];
                $entry = [
                    'name'         => optional($row['user'])->name ?? 'Unknown',
                    'total_bet'    => $row['total_bet'],
                    'total_payout' => $row['total_payout'],
                    'net'          => $net,
                ];
                if ($net > 0) {
                    $winners[] = $entry;
                } elseif ($net < 0) {
                    $losers[] = $entry;
                }
            }

            // Sort winners by net desc, losers by net asc
            usort($winners, fn($a, $b) => $b['net'] <=> $a['net']);
            usort($losers, fn($a, $b) => $a['net'] <=> $b['net']);

            $results = [
                'winners' => collect($winners),
                'losers'  => collect($losers),
            ];
        }

        return view('wagers.wager_detail', [
            'wager'    => $wager,
            'isJoined' => $isJoined,
            'userBet'  => $userBet,
            'results'  => $results,
        ]);
    }

    public function join(Wager $wager)
    {
        // Check if wager is already full
        if ($wager->isFull()) {
            return back()->with('error', 'This wager is already full.');
        }

        // Check if user has already joined
        if ($wager->hasPlayer(Auth::id())) {
            return back()->with('error', 'You have already joined this wager.');
        }

        // Add player to the wager
        $wager->players()->create([
            'user_id'    => Auth::id(),
            'status'     => 'pending',
            'bet_amount' => 0,
        ]);

        // Reload the wager with the updated players
        $wager->load('players');

        return redirect()->route('wagers.show', $wager)->with('success', 'You have successfully joined the wager!');
    }

    public function bet(Request $request, Wager $wager)
    {
        $request->validate([
            'bets' => 'required|array',
        ]);

        if ($wager->status === 'ended') {
            return back()->with('error', 'This wager has already ended.');
        }

        $wagerPlayer = $wager->players()->where('user_id', Auth::id())->first();
        if (! $wagerPlayer) {
            return back()->with('error', 'You need to join the wager before placing a bet.');
        }

        // Filter valid bets
        $validBets = [];
        foreach ($request->bets as $bet) {
            if (isset($bet['amount']) && $bet['amount'] > 0 && isset($bet['choice_id'])) {
                $validBets[] = $bet;
            }
        }

        if (empty($validBets)) {
            return back()->with('error', 'Please enter at least one bet amount.');
        }

        $totalBetAmount = array_sum(array_column($validBets, 'amount'));
        $user           = Auth::user();

        if ($totalBetAmount > $user->balance) {
            return back()->with('error', 'Insufficient balance.');
        }

        DB::beginTransaction();

        try {
            // Place new bets (accumulate)
            foreach ($validBets as $bet) {
                WagerChoice::where('id', $bet['choice_id'])->increment('total_bet', $bet['amount']);

                $wagerPlayer->bets()->create([
                    'wager_id'        => $wager->id,
                    'wager_choice_id' => $bet['choice_id'],
                    'bet_amount'      => $bet['amount'],
                    'status'          => 'pending',
                ]);
            }

            $wager->increment('pot', $totalBetAmount);
            $user->decrement('balance', $totalBetAmount);

            DB::commit();

            // Reload the wager with the latest data
            $wager->refresh();
            $wager->load(['choices', 'players']);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bets placed successfully!',
                    'wager'   => [
                        'pot'          => $wager->pot,
                        'choices'      => $wager->choices->map(function ($choice) {
                            return [
                                'id'        => $choice->id,
                                'label'     => $choice->label,
                                'total_bet' => $choice->total_bet,
                            ];
                        }),
                        'user_balance' => $user->fresh()->balance,
                    ],
                ]);
            }

            return back()->with('success', 'Bets placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bet error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized');
        }

        $wager->load('choices');
        return view('wagers.edit_wager', compact('wager'));
    }

    public function update(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to update this wager.');
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'max_players'     => 'required|integer|min:2|max:100',
            'ending_time'     => 'required|date|after:' . now()->addHour()->format('Y-m-d H:i'),
            'choices'         => 'required|array|min:2|max:10',
            'choices.*.id'    => 'nullable|exists:wager_choices,id',
            'choices.*.total_bet' => 'nullable|numeric|min:0',
            'choices.*.label' => 'required|string|max:255',
            'status'          => 'required|in:public,private',
        ]);

        DB::beginTransaction();

        try {
            // Update wager
            $wager->update([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'max_players' => $validated['max_players'],
                'ending_time' => $validated['ending_time'],
                'status'      => $validated['status'],
            ]);

            $existingChoiceIds = [];
            
            // Process each choice
            foreach ($validated['choices'] as $choiceData) {
                $updateData = ['label' => trim($choiceData['label'])];
                
                // Only update total_bet if it's provided in the request
                if (isset($choiceData['total_bet'])) {
                    $updateData['total_bet'] = (float)$choiceData['total_bet'];
                }
                
                // Update or create the choice
                $choice = $wager->choices()->updateOrCreate(
                    ['id' => $choiceData['id'] ?? null],
                    $updateData
                );
                
                $existingChoiceIds[] = $choice->id;
            }

            // Delete removed choices that have no bets
            $wager->choices()
                ->whereNotIn('id', $existingChoiceIds)
                ->where('total_bet', 0)
                ->delete();

            DB::commit();

            return redirect()->route('wagers.show', $wager)
                ->with('success', 'Wager updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating wager: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'An error occurred while updating the wager. Please try again.');
        }
    }

    public function destroy(Wager $wager)
    {
        // Check authorization
        if ($wager->creator_id !== Auth::id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
            }
            return back()->with('error', 'Not authorized to delete this wager.');
        }

        DB::beginTransaction();

        try {
            // First, refund all bets to users
            $bets = \App\Models\WagerBet::whereHas('wagerPlayer', function ($q) use ($wager) {
                $q->where('wager_id', $wager->id);
            })->with('wagerPlayer.user')->get();

            $refundedAmount = 0;
            foreach ($bets as $bet) {
                $user = $bet->wagerPlayer->user;
                if ($user) {
                    $user->increment('balance', $bet->bet_amount);
                    $refundedAmount += $bet->bet_amount;
                }
            }

            // Delete all bets
            \App\Models\WagerBet::whereHas('wagerPlayer', function ($q) use ($wager) {
                $q->where('wager_id', $wager->id);
            })->delete();

            // Delete all players
            $wager->players()->delete();

            // Delete all choices
            $wager->choices()->delete();

            // Finally delete the wager
            $wager->delete();

            DB::commit();

            Log::info("Wager {$wager->id} deleted. Refunded {$refundedAmount} to users.");

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Wager deleted successfully']);
            }

            return redirect()->route('wagers')->with('success', 'Wager deleted successfully and bets refunded.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting wager: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Error deleting wager: ' . $e->getMessage());
        }
    }

    // public function end(Request $request, Wager $wager)
    // {
    //     $validated = $request->validate([
    //         'winning_choice_id' => 'required|exists:wager_choices,id',
    //     ]);

    //     // Check if the user is the creator of the wager
    //     if ($wager->creator_id !== auth()->id()) {
    //         return back()->with('error', 'Only the wager creator can end the wager.');
    //     }

    //     // Check if the wager is already ended
    //     if ($wager->status === 'ended') {
    //         return back()->with('error', 'This wager has already ended.');
    //     }

    //     // Ensure the winning choice belongs to this wager
    //     if (! $wager->choices()->where('id', $validated['winning_choice_id'])->exists()) {
    //         return back()->with('error', 'Selected winning choice does not belong to this wager.');
    //     }

    //     // Start a database transaction
    //     DB::beginTransaction();

    //     try {
    //         // Update wager status and set winning choice
    //         $wager->update([
    //             'status'            => 'ended',
    //             'winning_choice_id' => $validated['winning_choice_id'],
    //         ]);

    //         $winningChoice    = WagerChoice::where('id', $validated['winning_choice_id'])
    //             ->where('wager_id', $wager->id)
    //             ->firstOrFail();
    //         $totalWinningBets = $winningChoice->total_bet;
    //         $totalPot         = $wager->pot;

    //         // If there are winning bets, distribute the pot
    //         if ($totalWinningBets > 0) {
    //             // Get all bets for this wager
    //             $bets = WagerBet::whereHas('wagerPlayer', function ($query) use ($wager) {
    //                 $query->where('wager_id', $wager->id);
    //             })->with('wagerPlayer.user')->get();

    //             // Group bets by user
    //             $userBets = [];
    //             foreach ($bets as $bet) {
    //                 $userId = $bet->wagerPlayer->user_id;

    //                 if (! isset($userBets[$userId])) {
    //                     $userBets[$userId] = [
    //                         'total_bet'   => 0,
    //                         'winning_bet' => 0,
    //                         'user'        => $bet->wagerPlayer->user,
    //                     ];
    //                 }

    //                 $userBets[$userId]['total_bet'] += $bet->bet_amount;

    //                 if ($bet->wager_choice_id == $winningChoice->id) {
    //                     $userBets[$userId]['winning_bet'] += $bet->bet_amount;
    //                 }

    //                 // Update bet status
    //                 $bet->update([
    //                     'status'        => $bet->wager_choice_id == $winningChoice->id ? 'won' : 'lost',
    //                     'actual_payout' => $bet->wager_choice_id == $winningChoice->id ?
    //                         ($bet->bet_amount / $totalWinningBets) * $totalPot : 0,
    //                 ]);
    //             }

    //             // Distribute winnings to users with winning bets
    //             foreach ($userBets as $userId => $userBet) {
    //                 if ($userBet['winning_bet'] > 0) {
    //                     // Calculate the user's share of the pot based on their winning bets
    //                     $winShare = ($userBet['winning_bet'] / $totalWinningBets) * $totalPot;
    //                     $profit   = $winShare - $userBet['total_bet'];

    //                     // Update user balance with their winnings
    //                     $prevBalance = $userBet['user']->balance;
    //                     $userBet['user']->increment('balance', $profit);

    //                     // Create a transaction record
    //                     Transaction::create([
    //                         'user_id'       => $userId,
    //                         'amount'        => $profit,
    //                         'type'          => 'wager_win',
    //                         'description'   => 'Wager winnings from ' . $wager->name,
    //                         'balance_after' => $prevBalance + $profit,
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Update all player statuses
    //         $wager->players()->update(['status' => 'completed']);

    //         DB::commit();

    //         event(new WagerEnded($wager, $winningChoice));

    //         return redirect()->route('wagers.show', $wager)
    //             ->with('success', 'Wager has been ended and winnings distributed successfully!');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error ending wager: ' . $e->getMessage());
    //         Log::error($e->getTraceAsString());

    //         return back()->with('error', 'An error occurred while processing the wager: ' . $e->getMessage());
    //     }
    // }

    public function stats(Wager $wager)
    {
        // Get all choices with their total bets
        $choices = $wager->choices()->withCount(['bets as bets_count' => function ($query) use ($wager) {
            $query->whereHas('wagerPlayer', function ($q) use ($wager) {
                $q->where('wager_id', $wager->id);
            });
        }])->get(['id', 'label', 'total_bet']);

        $totalPot       = $wager->pot;
        $distribution   = [];
        $totalBetsCount = 0;

        foreach ($choices as $choice) {
            $amount    = (float) $choice->total_bet;
            $betsCount = (int) $choice->bets_count;
            $totalBetsCount += $betsCount;

            $distribution[] = [
                'id'           => $choice->id,
                'label'        => $choice->label,
                'amount'       => $amount,
                'total_amount' => $amount,
                'percentage'   => $totalPot > 0 ? round(($amount / $totalPot) * 100, 2) : 0,
                'bets_count'   => $betsCount,
            ];
        }

        return response()->json([
            'pot'               => $totalPot,
            'distribution'      => $distribution,
            'status'            => $wager->status,
            'winning_choice_id' => $wager->winning_choice_id,
            'total_bets_count'  => $totalBetsCount,
        ]);
    }

    public function search(Request $request)
    {
        $q = trim($request->query('query', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $wagers = Wager::query()
            ->where('status', 'public')
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->latest()
            ->limit(20)
            ->get(['id', 'name', 'description', 'ending_time']);

        $data = $wagers->map(function ($w) {
            return [
                'id'          => $w->id,
                'name'        => $w->name,
                'description' => $w->description,
                'ends_human'  => optional($w->ending_time)->diffForHumans() ?? '',
            ];
        });

        return response()->json($data);
    }
}
