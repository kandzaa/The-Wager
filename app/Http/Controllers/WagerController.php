<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use App\Models\WagerBet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WagerController extends Controller
{
    public function index()
    {
        $wagers     = Wager::where('status', '!=', 'ended')->get();
        $userWagers = auth()->check()
            ? auth()->user()->wagers()->where('status', '!=', 'ended')->get()
            : collect([]);

        return view('wagers.lobby', [
            'wagers'     => $wagers,
            'userWagers' => $userWagers,
        ]);
    }

    public function history()
    {
        $wagers = Wager::with(['choices', 'winningChoice'])
            ->where('status', 'ended')
            ->latest('ended_at')
            ->paginate(10);

        return view('wagers.history', compact('wagers'));
    }

    public function create()
    {
        return view('wagers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'max_players'     => 'required|integer|min:2|max:100',
            'privacy'         => 'required|in:public,private',
            'starting_time'   => 'required|date',
            'ending_time'     => 'required|date|after:now',
            'choices.*.label' => 'required|string|max:255',
        ]);

        // FORCE proper status - lifecycle only
        $status = now()->greaterThanOrEqualTo($validated['starting_time']) ? 'active' : 'pending';

        $wager = Wager::create([
            'pot'           => 0,
            'name'          => $validated['name'],
            'description'   => $validated['description'],
            'max_players'   => $validated['max_players'],
            'status'        => $status,               // ← 'pending' or 'active' ONLY
            'privacy'       => $validated['privacy'], // 'public' or 'private'
            'starting_time' => $validated['starting_time'],
            'ending_time'   => $validated['ending_time'],
            'creator_id'    => auth()->id(),
        ]);

        foreach ($validated['choices'] as $choice) {
            $wager->choices()->create(['label' => $choice['label']]);
        }

        return response()->json(['message' => 'Wager created successfully'], 201);
    }

    public function edit(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to edit this wager.');
        }

        $wager->load('choices');
        return view('wagers.edit', compact('wager'));
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
            'ending_time'     => 'required|date|after:now',
            'choices'         => 'required|array|min:2|max:10',
            'choices.*.id'    => 'nullable|exists:wager_choices,id',
            'choices.*.label' => 'required|string|max:255',
            'status'          => 'required|in:pending,active',
        ]);

        DB::beginTransaction();

        try {
            $wager->update([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'max_players' => $validated['max_players'],
                'ending_time' => $validated['ending_time'],
                'status'      => $validated['status'],
            ]);

            $existingChoiceIds = [];

            foreach ($validated['choices'] as $choiceData) {
                $updateData = ['label' => trim($choiceData['label'])];

                if (isset($choiceData['total_bet'])) {
                    $updateData['total_bet'] = (float) $choiceData['total_bet'];
                }

                $choice = $wager->choices()->updateOrCreate(
                    ['id' => $choiceData['id'] ?? null],
                    $updateData
                );

                $existingChoiceIds[] = $choice->id;
            }

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

    public function join(Wager $wager)
    {
        if ($wager->hasPlayer(Auth::id())) {
            return back()->with('error', 'You have already joined this wager.');
        }

        if ($wager->isFull()) {
            return back()->with('error', 'This wager is already full.');
        }

        try {
            $wager->players()->create([
                'user_id'    => Auth::id(),
                'bet_amount' => 0,
            ]);

            if ($wager->status !== 'active' && now()->greaterThanOrEqualTo($wager->starting_time)) {
                $wager->update(['status' => 'active']);
            }

            return back()->with('success', 'You have joined the wager!');

        } catch (\Exception $e) {
            Log::error('Error joining wager: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while joining the wager.');
        }
    }

    public function bet(Request $request, Wager $wager)
    {
        $validated = $request->validate([
            'bets'             => 'required|array',
            'bets.*.choice_id' => 'required|exists:wager_choices,id',
            'bets.*.amount'    => 'required|integer|min:1|max:' . Auth::user()->balance,
        ]);

        if ($wager->status === 'ended') {
            return response()->json([
                'success' => false,
                'message' => 'This wager has ended.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $user        = Auth::user();
            $totalAmount = 0;
            $bets        = [];

            foreach ($validated['bets'] as $betData) {
                $choice = $wager->choices()->findOrFail($betData['choice_id']);
                $amount = (int) $betData['amount'];
                $totalAmount += $amount;

                // Get or create player and lock the row for update
                $player = $wager->players()->firstOrCreate(
                    ['user_id' => $user->id],
                    ['bet_amount' => 0]
                );

                // Create the bet
                $bet = new WagerBet([
                    'wager_id'        => $wager->id,
                    'wager_choice_id' => $choice->id,
                    'wager_player_id' => $player->id,
                    'bet_amount'      => $amount,
                    'amount'          => $amount,
                    'status'          => 'pending',
                ]);
                $bet->save();

                $bets[] = $bet;

                // Update the choice's total bet
                $choice->increment('total_bet', $amount);
            }

            // Decrement user balance by the total amount once
            $user->decrement('balance', $totalAmount);

            // Update the player's total bet amount
            $player->increment('bet_amount', $totalAmount);

            DB::commit();

            // Get fresh data
            $user->refresh();
            $wager->refresh();

            // Get updated stats
            $stats = $this->stats($wager)->getData(true);

            return response()->json([
                'success'      => true,
                'message'      => 'Bets placed successfully!',
                'bets'         => $bets,
                'pot'          => $wager->pot,
                'distribution' => $stats['distribution'] ?? [],
                'wager'        => [
                    'user_balance' => $user->balance,
                    'status'       => $wager->status,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bet placement failed: ' . $e->getMessage() . '\n' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to place bets. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showEndForm(Wager $wager)
    {
        if ($wager->creator_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the wager creator can end the wager.',
                ], 403);
            }
            return back()->with('error', 'Only the wager creator can end the wager.');
        }

        if ($wager->status === 'ended') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This wager has already ended.',
                ], 400);
            }
            return back()->with('error', 'This wager has already ended.');
        }

        $wager->load('choices');

        if (request()->ajax() || request()->wantsJson()) {
            return view('wagers.wagers_end', compact('wager'))->render();
        }

        return view('wagers.wagers_end', compact('wager'));
    }

    public function end(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Only the wager creator can end the wager.',
            ], 403);
        }

        if ($wager->status === 'ended') {
            return response()->json([
                'success' => false,
                'message' => 'This wager has already ended.',
            ], 400);
        }

        $validated = $request->validate([
            'winning_choice_id' => 'required|exists:wager_choices,id,wager_id,' . $wager->id,
        ]);

        DB::beginTransaction();

        try {
            $winningChoice    = $wager->choices()->findOrFail($validated['winning_choice_id']);
            $totalPot         = $wager->pot;
            $winningBets      = $wager->bets()->where('wager_choice_id', $winningChoice->id)->with('wagerPlayer.user')->get();
            $totalWinningBets = $winningBets->sum('bet_amount');

            $wager->update([
                'status'            => 'ended',
                'winning_choice_id' => $winningChoice->id,
                'ended_at'          => now(),
            ]);

            // Calculate 1.5x multiplier for winning bets
            $payoutMultiplier = 1.5;

            foreach ($wager->bets as $bet) {
                $isWinner = $bet->wager_choice_id === $winningChoice->id;

                if ($isWinner) {
                    $payout    = $bet->bet_amount * $payoutMultiplier;
                    $winAmount = $payout - $bet->bet_amount;

                    $bet->wagerPlayer->user->increment('balance', $payout);
                    $bet->update([
                        'is_win'     => true,
                        'payout'     => $payout,
                        'won_amount' => $winAmount,
                    ]);
                } else {
                    $bet->update([
                        'is_win'     => false,
                        'payout'     => 0,
                        'won_amount' => 0,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Wager ended successfully!',
                'redirect' => route('wagers.results', $wager),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error ending wager: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to end wager. ' . ($e->getMessage() ?? 'Please try again.'),
            ], 500);
        }
    }

    public function destroy(Wager $wager)
    {
        try {
            DB::beginTransaction();
            $refundedAmount = 0;

            foreach ($wager->bets as $bet) {
                $bet->user->increment('balance', $bet->amount);
                $refundedAmount += $bet->amount;
                $bet->delete();
            }

            $wager->delete();

            DB::commit();

            Log::info("Wager {$wager->id} deleted. Refunded {$refundedAmount} to users.");

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Wager deleted successfully']);
            }

            return redirect()->route('wagers.index')
                ->with('success', 'Wager deleted successfully and bets refunded.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting wager: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete wager'], 500);
            }

            return back()->with('error', 'Failed to delete wager. Please try again.');
        }
    }

    public function sendInvitation(Request $request, Wager $wager)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Only the wager creator can send invitations.');
        }

        if ($wager->isFull()) {
            return back()->with('error', 'This wager is already full.');
        }

        $friend = User::findOrFail($request->friend_id);

        if ($wager->hasPlayer($friend->id)) {
            return back()->with('error', 'This user is already a participant in this wager.');
        }

        if ($wager->isUserInvited($friend->email)) {
            return back()->with('error', 'An invitation has already been sent to this user.');
        }

        if (! Auth::user()->friends->contains('id', $friend->id)) {
            return back()->with('error', 'You can only invite your friends to this wager.');
        }

        $invitation             = $wager->inviteUser($friend->email, Auth::id());
        $invitation->invitee_id = $friend->id;
        $invitation->save();

        return back()->with('success', 'Invitation sent to ' . $friend->name . '!');
    }

    public function acceptInvitation($token)
    {
        $invitation = WagerInvitation::where('token', $token)
            ->where('status', WagerInvitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $wager = $invitation->wager;
        $user  = Auth::user();

        if ($wager->hasPlayer($user->id)) {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'You are already a participant in this wager.');
        }

        if ($wager->isFull()) {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'This wager is already full.');
        }

        $invitation->update([
            'status'      => WagerInvitation::STATUS_ACCEPTED,
            'invitee_id'  => $user->id,
            'accepted_at' => now(),
        ]);

        $wager->players()->create([
            'user_id'    => $user->id,
            'bet_amount' => 0,
        ]);

        return redirect()->route('wagers.show', $wager)
            ->with('success', 'You have successfully joined the wager!');
    }

    public function declineInvitation($token)
    {
        $invitation = WagerInvitation::where('token', $token)
            ->where('status', WagerInvitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $invitation->update([
            'status'      => WagerInvitation::STATUS_DECLINED,
            'declined_at' => now(),
        ]);

        return redirect()->route('wagers')
            ->with('status', 'You have declined the invitation.');
    }

    public function search(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (empty($query)) {
            return response()->json(['wagers' => []]);
        }

        $wagers = Wager::with(['creator', 'choices'])
            ->where('status', 'public')
            ->where('status', '!=', 'ended')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->where('ending_time', '>', now())
            ->latest()
            ->limit(20)
            ->get();

        $data = [
            'wagers' => $wagers->map(function ($wager) {
                return [
                    'id'          => $wager->id,
                    'name'        => $wager->name,
                    'description' => $wager->description,
                    'ending_time' => $wager->ending_time,
                    'creator'     => $wager->creator ? $wager->creator->name : 'Unknown',
                    'choices'     => $wager->choices->map(function ($choice) {
                        return [
                            'id'    => $choice->id,
                            'label' => $choice->label,
                        ];
                    }),
                ];
            })->toArray(),
        ];

        return response()->json($data);
    }

    public function show(Wager $wager)
    {
        $friends            = Auth::user()->friends;
        $pendingInvitations = $wager->invitations()->where('status', 'pending')->get();
        $isJoined           = $wager->players()->where('user_id', Auth::id())->exists();
        $results            = $wager->status === 'ended' ? $this->getWagerResults($wager) : null;

        return view('wagers.wager_detail', compact('wager', 'friends', 'pendingInvitations', 'isJoined', 'results'));
    }

    protected function getWagerResults(Wager $wager)
    {
        $wager->load(['players.user', 'bets.wagerChoice']);
        $bets             = $wager->bets;
        $winningChoiceId  = $wager->winning_choice_id;
        $totalPot         = $wager->players->sum('bet_amount');
        $totalWinningBets = $bets->where('wager_choice_id', $winningChoiceId)->sum('bet_amount');

        $results = [];
        foreach ($bets->groupBy('wager_player_id') as $playerId => $playerBets) {
            $player     = $playerBets->first()->wagerPlayer->user;
            $userResult = [
                'name' => $player->name,
                'net'  => 0,
            ];

            foreach ($playerBets as $bet) {
                $isWinner = $bet->wager_choice_id == $winningChoiceId;
                $payout   = $isWinner && $totalWinningBets > 0
                    ? ($bet->bet_amount / $totalWinningBets) * $totalPot
                    : 0;
                $net = $payout - $bet->bet_amount;
                $userResult['net'] += $net;
            }

            $results[] = $userResult;
        }

        $winners = array_filter($results, fn($r) => $r['net'] > 0);
        $losers  = array_filter($results, fn($r) => $r['net'] <= 0);

        return [
            'winners' => array_values($winners),
            'losers'  => array_values($losers),
        ];
    }

    public function results(Wager $wager)
    {
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.show', $wager)
                ->with('error', 'This wager has not ended yet.');
        }

        $wager->load(['winningChoice', 'choices', 'creator', 'players.user', 'bets.wagerChoice']);

        $bets             = $wager->bets()->with(['wagerPlayer.user', 'wagerChoice'])->get();
        $winningChoice    = $wager->winningChoice;
        $totalWinningBets = $winningChoice->total_bet;
        $totalPot         = $wager->players->sum('bet_amount');

        $results = [];
        foreach ($bets->groupBy('wagerPlayer.user_id') as $userId => $userBets) {
            $user             = $userBets->first()->wagerPlayer->user;
            $payoutMultiplier = 1.5;
            $userResult       = [
                'user'      => $user,
                'total_bet' => 0,
                'payout'    => 0,
                'profit'    => 0,
                'status'    => 'lost',
                'bets'      => [],
            ];

            foreach ($userBets as $bet) {
                $isWinner = $bet->wager_choice_id === $winningChoice->id;
                $payout   = $isWinner ? $bet->bet_amount * $payoutMultiplier : 0;
                $profit   = $payout - $bet->bet_amount;

                $userResult['bets'][] = [
                    'choice'    => $bet->wagerChoice->label,
                    'amount'    => $bet->bet_amount,
                    'is_winner' => $isWinner,
                    'payout'    => $payout,
                    'profit'    => $profit,
                ];

                $userResult['total_bet'] += $bet->bet_amount;
                $userResult['payout'] += $payout;
                $userResult['profit'] += $profit;

                if ($isWinner) {
                    $userResult['status'] = 'won';
                }
            }

            $results[] = $userResult;
        }

        $results = collect($results)->sortByDesc('payout');

        return view('wagers.results', [
            'wager'         => $wager,
            'winningChoice' => $winningChoice,
            'results'       => $results,
        ]);
    }

    public function stats(Wager $wager)
    {
        $choices = $wager->choices()->get(['id', 'label', 'total_bet']);

        $totalPot     = $wager->pot;
        $distribution = [];

        foreach ($choices as $choice) {
            $amount = (float) $choice->total_bet;

            $distribution[] = [
                'id'           => $choice->id,
                'label'        => $choice->label,
                'amount'       => $amount,
                'total_amount' => $amount,
                'percentage'   => $totalPot > 0 ? round(($amount / $totalPot) * 100, 2) : 0,
            ];
        }

        return response()->json([
            'pot'               => $totalPot,
            'distribution'      => $distribution,
            'status'            => $wager->status,
            'winning_choice_id' => $wager->winning_choice_id,
        ]);
    }
}
