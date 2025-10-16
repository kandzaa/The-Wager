<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WagerController extends Controller
{
    public function index()
    {
        $wagers = Wager::with('creator')
            ->public()
            ->orderBy('ending_time', 'asc')
            ->get();

        $userWagers = auth()->check()
            ? Wager::with('creator')
            ->where('creator_id', auth()->id())
            ->orderBy('ending_time', 'asc')
            ->get()
            : collect();

        return view('wagers.lobby', compact('wagers', 'userWagers'));
    }

    public function history()
    {
        // Get only ended wagers
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
            'description'     => 'nullable|string',
            'max_players'     => 'required|integer|min:2|max:100',
            'ending_time'     => 'required|date|after:now',
            'choices'         => 'required|array|min:2|max:10',
            'choices.*.label' => 'required|string|max:255',
            'status'          => 'required|in:public,private',
        ]);

        DB::beginTransaction();

        try {
            $wager = Wager::create([
                'creator_id'  => Auth::id(),
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'max_players' => $validated['max_players'],
                'ending_time' => $validated['ending_time'],
                'status'      => $validated['status'],
            ]);

            foreach ($validated['choices'] as $choice) {
                $wager->choices()->create([
                    'label'     => $choice['label'],
                    'total_bet' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('wagers.show', $wager)
                ->with('success', 'Wager created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating wager: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'An error occurred while creating the wager. Please try again.');
        }
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
            'status'          => 'required|in:public,private',
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
        if ($wager->creator_id !== Auth::id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
            }
            return back()->with('error', 'Not authorized to delete this wager.');
        }

        DB::beginTransaction();

        try {
            // Refund all bets
            $refundedAmount = 0;
            foreach ($wager->players as $player) {
                if ($player->user) {
                    $player->user->increment('balance', $player->bet_amount);
                    $refundedAmount += $player->bet_amount;
                }
            }

            // Delete all related records
            $wager->bets()->delete();
            $wager->players()->delete();
            $wager->choices()->delete();
            $wager->delete();

            DB::commit();

            Log::info("Wager {$wager->id} deleted. Refunded {$refundedAmount} to users.");

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Wager deleted successfully']);
            }

            return redirect()->route('wagers')
                ->with('success', 'Wager deleted successfully and bets refunded.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting wager: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error deleting wager: ' . $e->getMessage());
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

            return back()->with('success', 'You have joined the wager!');

        } catch (\Exception $e) {
            Log::error('Error joining wager: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while joining the wager.');
        }
    }

    public function bet(Request $request, Wager $wager)
    {
        $validated = $request->validate([
            'choice_id' => 'required|exists:wager_choices,id,wager_id,' . $wager->id,
            'amount'    => 'required|numeric|min:1|max:' . Auth::user()->balance,
        ]);

        if ($wager->status !== 'active') {
            return back()->with('error', 'This wager is not currently accepting bets.');
        }

        DB::beginTransaction();

        try {
            $player = $wager->players()->where('user_id', Auth::id())->firstOrFail();
            $choice = $wager->choices()->findOrFail($validated['choice_id']);

            // Deduct bet amount from user's balance
            Auth::user()->decrement('balance', $validated['amount']);

            // Record the bet
            $bet = $player->bets()->create([
                'wager_choice_id' => $choice->id,
                'bet_amount'      => $validated['amount'],
            ]);

            // Update player's total bet
            $player->increment('bet_amount', $validated['amount']);

            // Update choice's total bet
            $choice->increment('total_bet', $validated['amount']);

            DB::commit();

            return back()->with('success', 'Bet placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error placing bet: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while placing your bet.');
        }
    }

    public function showEndForm(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            abort(403, 'Only the wager creator can end the wager.');
        }

        $wager->load('choices');
        return view('wagers.end', compact('wager'));
    }

    public function end(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Only the wager creator can end the wager.');
        }

        if ($wager->status === 'ended') {
            return redirect()->route('wagers.results', $wager)
                ->with('info', 'This wager has already ended.');
        }

        $validated = $request->validate([
            'winning_choice_id' => 'required|exists:wager_choices,id,wager_id,' . $wager->id,
        ]);

        DB::beginTransaction();

        try {
            $winningChoice    = $wager->choices()->findOrFail($validated['winning_choice_id']);
            $totalPot         = $wager->players()->sum('bet_amount');
            $winningBets      = $wager->bets()->where('wager_choice_id', $winningChoice->id)->get();
            $totalWinningBets = $winningBets->sum('bet_amount');

            // Calculate and distribute winnings
            foreach ($winningBets as $bet) {
                if ($totalWinningBets > 0) {
                    $payoutRatio = $bet->bet_amount / $totalWinningBets;
                    $payout      = $totalPot * $payoutRatio;

                    $bet->update(['actual_payout' => $payout]);
                    $bet->wagerPlayer->user->increment('balance', $payout);
                }
            }

            // Mark wager as ended
            $wager->update([
                'status'            => 'ended',
                'winning_choice_id' => $winningChoice->id,
                'ended_at'          => now(),
            ]);

            DB::commit();

            return redirect()->route('wagers.results', $wager)
                ->with('success', 'Wager ended successfully and winnings distributed!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error ending wager: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while ending the wager.');
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
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
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
        $wager->load(['choices', 'creator', 'bets.user']);

        $userBet = null;
        if (auth()->check()) {
            $userBet = $wager->bets->firstWhere('user_id', auth()->id());
        }

        return view('wagers.show', [
            'wager'   => $wager,
            'userBet' => $userBet,
        ]);
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
            $user       = $userBets->first()->wagerPlayer->user;
            $userResult = [
                'user'      => $user,
                'total_bet' => 0,
                'payout'    => 0,
                'profit'    => 0,
                'status'    => 'lost',
                'bets'      => [],
            ];

            foreach ($userBets as $bet) {
                $isWinner = $bet->wager_choice_id === $winningChoice->id;
                $payout   = $isWinner && $totalWinningBets > 0
                    ? ($bet->bet_amount / $totalWinningBets) * $totalPot
                    : 0;
                $profit = $payout - $bet->bet_amount;

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

                if ($isWinner && $payout > 0) {
                    $userResult['status'] = 'won';
                }
            }

            $results[] = $userResult;
        }

        // Sort by payout descending
        $results = collect($results)->sortByDesc('payout');

        return view('wagers.results', [
            'wager'         => $wager,
            'winningChoice' => $winningChoice,
            'results'       => $results,
        ]);
    }

    public function stats(Wager $wager)
    {
        $choices = $wager->choices()->withCount(['bets' => function ($query) use ($wager) {
            $query->whereHas('wagerPlayer', function ($q) use ($wager) {
                $q->where('wager_id', $wager->id);
            });
        }])->get(['id', 'label', 'total_bet']);

        $totalPot       = $wager->players->sum('bet_amount');
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
}
