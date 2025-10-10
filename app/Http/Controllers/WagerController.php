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
        // Get active wagers (not ended)
        $wagers = Wager::with('choices')
            ->where('status', '!=', 'ended')
            ->latest()
            ->get();
            
        return view('wagers.lobby', compact('wagers'));
    }
    
    public function history()
    {
        // Get only ended wagers
        $wagers = Wager::with(['choices', 'winningChoice'])
            ->where('status', 'ended')
            ->latest('ended_at')
            ->paginate(10);
            
        return view('history', compact('wagers'));
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
        // Load related data
        $wager->load([
            'choices' => function ($query) {
                $query->orderBy('id');
            },
            'players' => function ($query) {
                $query->with('user');
            },
        ]);

        // Get pending invitations for this wager
        $pendingInvitations = $wager->pendingInvitations()->get();

        // Get creator's friends who are not already in the wager
        $friends = auth()->user()->friends()
            ->whereDoesntHave('wagerPlayers', function ($query) use ($wager) {
                $query->where('wager_id', $wager->id);
            })
            ->whereDoesntHave('wagerInvitations', function ($query) use ($wager) {
                $query->where('wager_id', $wager->id)
                    ->where('status', 'pending');
            })
            ->get();

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
            'wager'              => $wager,
            'isJoined'           => $isJoined,
            'userBet'            => $userBet,
            'results'            => $results,
            'pendingInvitations' => $pendingInvitations,
            'friends'            => $friends,
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
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'max_players'         => 'required|integer|min:2|max:100',
            'ending_time'         => 'required|date|after:' . now()->addHour()->format('Y-m-d H:i'),
            'choices'             => 'required|array|min:2|max:10',
            'choices.*.id'        => 'nullable|exists:wager_choices,id',
            'choices.*.total_bet' => 'nullable|numeric|min:0',
            'choices.*.label'     => 'required|string|max:255',
            'status'              => 'required|in:public,private',
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
                    $updateData['total_bet'] = (float) $choiceData['total_bet'];
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

    public function end(Request $request, Wager $wager)
    {
        \Log::info('=== STARTING TO END WAGER ===', [
            'wager_id'       => $wager->id,
            'wager_name'     => $wager->name,
            'user_id'        => auth()->id(),
            'current_status' => $wager->status,
            'input'          => $request->all(),
        ]);

        // Check if the user is the creator of the wager
        if ($wager->creator_id !== auth()->id()) {
            \Log::warning('Unauthorized attempt to end wager', [
                'user_id' => auth()->id(),
                'wager_creator_id' => $wager->creator_id
            ]);
            return back()->with('error', 'Only the wager creator can end the wager.');
        }

        // Check if the wager is already ended
        if ($wager->status === 'ended') {
            \Log::info('Wager already ended', ['wager_id' => $wager->id]);
            return redirect()->route('wagers.results', $wager)
                ->with('info', 'This wager has already ended.');
        }

        // Validate the request
        try {
            $validated = $request->validate([
                'winning_choice_id' => 'required|exists:wager_choices,id,wager_id,' . $wager->id,
            ]);
            \Log::debug('Validation passed', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }


        // Get the winning choice (already validated it exists and belongs to this wager)
        $winningChoice = WagerChoice::find($validated['winning_choice_id']);

        DB::beginTransaction();

        try {
            // Update wager status, set winning choice, and mark as ended
            $endedAt = now();
            $wager->update([
                'status'            => 'ended',
                'winning_choice_id' => $winningChoice->id,
                'ended_at'          => $endedAt->toDateTimeString(),
            ]);
            
            // Refresh the wager model to ensure we have the latest data
            $wager->refresh();

            // Get all bets for this wager
            $bets = \App\Models\WagerBet::whereHas('wagerPlayer', function ($query) use ($wager) {
                $query->where('wager_id', $wager->id);
            })->with('wagerPlayer.user')->get();

            $totalWinningBets = $winningChoice->total_bet;
            $totalPot = $wager->pot;

            // Payout multiplier (1.5x the bet amount for winners)
            $payoutMultiplier = 1.5;
            
            // Track total payouts for logging
            $totalPayouts = 0;
            $totalWinningBetsAmount = 0;
            
            // First pass: Calculate total winning bets amount and update bet statuses
            foreach ($bets as $bet) {
                if ($bet->wager_choice_id == $winningChoice->id) {
                    $totalWinningBetsAmount += $bet->bet_amount;
                    $bet->update([
                        'status' => 'won',
                        'actual_payout' => round($bet->bet_amount * $payoutMultiplier, 2)
                    ]);
                } else {
                    $bet->update([
                        'status' => 'lost',
                        'actual_payout' => 0
                    ]);
                }
            }
            
            // Second pass: Distribute winnings to winners
            if ($totalWinningBetsAmount > 0) {
                foreach ($bets as $bet) {
                    if ($bet->wager_choice_id == $winningChoice->id) {
                        $payout = round($bet->bet_amount * $payoutMultiplier, 2);
                        $totalPayouts += $payout;
                        
                        // Update user balance with their winnings
                        $bet->wagerPlayer->user->increment('balance', $payout);
                        
                        \Log::info('Paid out winnings', [
                            'user_id' => $bet->wagerPlayer->user_id,
                            'bet_amount' => $bet->bet_amount,
                            'payout' => $payout,
                            'multiplier' => $payoutMultiplier
                        ]);
                    }
                }
                
                // Log house profit (total pot - total payouts)
                $houseProfit = $totalPot - $totalPayouts;
                \Log::info('Wager completed with house profit', [
                    'wager_id' => $wager->id,
                    'total_pot' => $totalPot,
                    'total_payouts' => $totalPayouts,
                    'house_profit' => $houseProfit,
                    'payout_multiplier' => $payoutMultiplier
                ]);
            } else {
                // No winning bets - mark all as lost with 0 payout
                foreach ($bets as $bet) {
                    $bet->update([
                        'status' => 'lost',
                        'actual_payout' => 0
                    ]);
                }
            }

            // Update player statuses based on their bets
            $wager->players()->each(function($player) use ($winningChoice) {
                $status = 'lost'; // Default to lost
                if ($player->choice_id === $winningChoice->id) {
                    $status = 'won';
                }
                $player->update(['status' => $status]);
            });

            DB::commit();

            \Log::info('Successfully ended wager', [
                'wager_id' => $wager->id,
                'winning_choice_id' => $winningChoice->id,
                'total_pot' => $totalPot,
                'total_winning_bets' => $totalWinningBets
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wager has been ended and winnings distributed successfully!',
                    'redirect' => route('wagers.results', $wager)
                ]);
            }
            
            return redirect()->route('wagers.results', $wager)
                ->with('success', 'Wager has been ended and winnings distributed successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error ending wager: ' . $e->getMessage(), [
                'wager_id' => $wager->id,
                'errors' => $e->errors(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: ' . $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error ending wager: ' . $e->getMessage(), [
                'wager_id'  => $wager->id,
                'exception' => $e->getTraceAsString(),
                'class' => get_class($e)
            ]);

            $errorMessage = 'An error occurred while processing the wager: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => [
                        'message' => $e->getMessage(),
                        'type' => get_class($e)
                    ]
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function showEndForm(Wager $wager)
    {
        // Ensure only the creator can access this
        if ($wager->creator_id !== auth()->id()) {
            abort(403, 'Only the wager creator can end the wager.');
        }

        // Load the wager with its choices
        $wager->load('choices');

        // Redirect to the wager detail page with the modal open
        return redirect()->route('wagers.show', $wager)->with('show_end_modal', true);
    }

    public function results(Wager $wager)
    {
        // Only allow viewing results if the wager has ended
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'This wager has not ended yet.');
        }

        // Eager load all necessary relationships
        $wager->load(['winningChoice', 'choices', 'creator']);

        // Get all bets for this wager with related data
        $bets = \App\Models\WagerBet::whereHas('wagerPlayer', function ($q) use ($wager) {
            $q->where('wager_id', $wager->id);
        })->with(['wagerPlayer.user', 'wagerChoice'])->get();

        $winningChoice    = $wager->winningChoice;
        $totalWinningBets = $winningChoice->total_bet;
        $totalPot         = $wager->pot;

        // Group bets by user
        $results = [];

        foreach ($bets as $bet) {
            $userId   = $bet->wagerPlayer->user_id;
            $user     = $bet->wagerPlayer->user;
            $isWinner = $bet->wager_choice_id === $winningChoice->id;

            // Initialize user in results if not exists
            if (! isset($results[$userId])) {
                $results[$userId] = [
                    'user'      => $user,
                    'total_bet' => 0,
                    'payout'    => 0,
                    'profit'    => 0,
                    'status'    => 'lost',
                    'bets'      => [],
                ];
            }

            $payout = (float) $bet->actual_payout;
            $profit = $payout - $bet->bet_amount;

            // Track each bet separately
            $results[$userId]['bets'][] = [
                'choice'    => $bet->wagerChoice->label,
                'amount'    => (float) $bet->bet_amount,
                'is_winner' => $isWinner,
                'payout'    => $payout,
                'profit'    => $profit,
            ];

            // Update user totals
            $results[$userId]['total_bet'] += (float) $bet->bet_amount;
            $results[$userId]['payout'] += $payout;
            $results[$userId]['profit'] += $profit;

            if ($isWinner && $payout > 0) {
                $results[$userId]['status'] = 'won';
            }
        }

        // Sort by payout descending (winners first)
        $results = collect($results)->sortByDesc('payout');

        return view('wagers.results', [
            'wager'         => $wager,
            'winningChoice' => $winningChoice,
            'results'       => $results,
        ]);
    }
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

    /**
     * Send an invitation to join the wager.
     */
    public function sendInvitation(Request $request, Wager $wager)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        // Check if user is the creator
        if ($wager->creator_id !== auth()->id()) {
            return back()->with('error', 'Only the wager creator can send invitations.');
        }

        // Check if wager is full
        if ($wager->isFull()) {
            return back()->with('error', 'This wager is already full.');
        }

        $friend = \App\Models\User::findOrFail($request->friend_id);

        // Check if user is already a player
        if ($wager->hasPlayer($friend->id)) {
            return back()->with('error', 'This user is already a participant in this wager.');
        }

        // Check if invitation already exists
        if ($wager->isUserInvited($friend->email)) {
            return back()->with('error', 'An invitation has already been sent to this user.');
        }

        // Check if friend is actually a friend
        if (! auth()->user()->friends->contains('id', $friend->id)) {
            return back()->with('error', 'You can only invite your friends to this wager.');
        }

        // Create and send invitation
        $invitation             = $wager->inviteUser($friend->email, auth()->id());
        $invitation->invitee_id = $friend->id;
        $invitation->save();

        // Here you might want to send a notification to the friend

        return back()->with('success', 'Invitation sent to ' . $friend->name . '!');
    }

    /**
     * Accept an invitation to join a wager.
     */
    public function acceptInvitation($token)
    {
        $invitation = \App\Models\WagerInvitation::where('token', $token)
            ->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $wager = $invitation->wager;
        $user  = auth()->user();

        // Check if user is already a player
        if ($wager->hasPlayer($user->id)) {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'You are already a participant in this wager.');
        }

        // Check if wager is full
        if ($wager->isFull()) {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'This wager is already full.');
        }

        // Mark invitation as accepted
        $invitation->update([
            'status'      => \App\Models\WagerInvitation::STATUS_ACCEPTED,
            'invitee_id'  => $user->id,
            'accepted_at' => now(),
        ]);

        // Add user to wager players
        $wager->players()->create([
            'user_id'    => $user->id,
            'bet_amount' => 0, // Initial bet amount
        ]);

        return redirect()->route('wagers.show', $wager)
            ->with('success', 'You have successfully joined the wager!');
    }

    /**
     * Decline an invitation to join a wager.
     */
    public function declineInvitation($token)
    {
        $invitation = \App\Models\WagerInvitation::where('token', $token)
            ->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Mark invitation as declined
        $invitation->update([
            'status'      => \App\Models\WagerInvitation::STATUS_DECLINED,
            'declined_at' => now(),
        ]);

        return redirect()->route('wagers')
            ->with('status', 'You have declined the invitation.');
    }
}
