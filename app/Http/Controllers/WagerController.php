<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use App\Models\WagerBet;
use App\Models\WagerInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WagerController extends Controller
{
    public function index()
    {
        $wagers     = Wager::where('status', '!=', 'ended')->where('privacy', 'public')->get();
        $userWagers = auth()->check()
            ? auth()->user()->wagers()->where('status', '!=', 'ended')->get()
            : collect([]);

        return view('wagers.lobby', [
            'wagers'     => $wagers,
            'userWagers' => $userWagers,
        ]);
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
            'ending_time'     => 'required|date|after:starting_time',
            'choices.*.label' => 'required|string|max:255',
        ]);

        $status = now()->greaterThanOrEqualTo($validated['starting_time']) ? 'active' : 'pending';
        if (! in_array($status, ['pending', 'active', 'ended'])) {
            throw new \Exception('Invalid status: ' . $status);
        }

        \Log::emergency('WAGER DEBUG', [
            'validated_privacy' => $validated['privacy'],
            'calculated_status' => $status,
            'request_all'       => $request->all(),
        ]);

        $wagerId = DB::table('wagers')->insertGetId([
            'pot'           => 0,
            'name'          => $validated['name'],
            'description'   => $validated['description'],
            'max_players'   => $validated['max_players'],
            'status'        => $status,
            'privacy'       => $validated['privacy'],
            'starting_time' => $validated['starting_time'],
            'ending_time'   => $validated['ending_time'],
            'creator_id'    => auth()->id(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $wager = Wager::find($wagerId);

        foreach ($validated['choices'] as $choice) {
            $wager->choices()->create(['label' => $choice['label']]);
        }

        return response()->json(['message' => 'Wager created successfully', 'id' => $wagerId], 201);
    }

    public function edit(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to edit this wager.');
        }

        if ($wager->status === 'ended') {
            return back()->with('error', 'Cannot edit an ended wager.');
        }

        $choices = DB::table('wager_choices')
            ->where('wager_id', $wager->id)
            ->orderBy('id')
            ->get();

        $wager->choices = $choices;

        return view('wagers.edit', compact('wager'));
    }

   public function update(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to update this wager.');
        }

        if ($wager->status === 'ended') {
            return back()->with('error', 'Cannot edit an ended wager.');
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'max_players'     => 'required|integer|min:2|max:100',
            'privacy'         => 'required|in:public,private',
            'starting_time'   => 'required|date',
            'ending_time'     => 'required|date|after:starting_time',
            'choices'         => 'required|array|min:2|max:10',
            'choices.*.id'    => 'nullable',
            'choices.*.label' => 'required|string|max:255',
        ]);

        $startingTime = \Carbon\Carbon::parse($validated['starting_time'])->format('Y-m-d H:i:s');
        $endingTime   = \Carbon\Carbon::parse($validated['ending_time'])->format('Y-m-d H:i:s');

        // Outside transaction so a failing wager UPDATE doesn't poison subsequent queries
        $existingIds = DB::table('wager_choices')
            ->where('wager_id', $wager->id)
            ->pluck('id')
            ->toArray();

        try {
Log::emergency('ABOUT TO UPDATE WAGER', [
    'wager_id'     => $wager->id,
    'columns'      => DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'wagers'"),
    'update_data'  => [
        'name'          => $validated['name'],
        'description'   => $validated['description'] ?? null,
        'max_players'   => (int) $validated['max_players'],
        'privacy'       => $validated['privacy'],
        'starting_time' => $startingTime,
        'ending_time'   => $endingTime,
    ],
]);
            DB::beginTransaction();

            // NO updated_at — wagers table has $timestamps = false
            DB::table('wagers')->where('id', $wager->id)->update([
                'name'          => $validated['name'],
                'description'   => $validated['description'] ?? null,
                'max_players'   => (int) $validated['max_players'],
                'privacy'       => $validated['privacy'],
                'starting_time' => $startingTime,
                'ending_time'   => $endingTime,
            ]);

            $processedIds = [];

            foreach ($validated['choices'] as $choice) {
                $label    = trim((string) $choice['label']);
                $choiceId = !empty($choice['id']) ? (int) $choice['id'] : null;

                if (empty($label)) continue;

                if ($choiceId && in_array($choiceId, $existingIds)) {
                    // NO updated_at — wager_choices also has no timestamps
                    DB::table('wager_choices')
                        ->where('id', $choiceId)
                        ->where('wager_id', $wager->id)
                        ->update(['label' => $label]);
                    $processedIds[] = $choiceId;
                } else {
                    // NO created_at/updated_at on insert either
                    $newId = DB::table('wager_choices')->insertGetId([
                        'wager_id'  => $wager->id,
                        'label'     => $label,
                        'total_bet' => 0,
                    ]);
                    $processedIds[] = $newId;
                }
            }

            $toDelete = array_diff($existingIds, $processedIds);
            if (!empty($toDelete)) {
                DB::table('wager_choices')
                    ->whereIn('id', $toDelete)
                    ->where('wager_id', $wager->id)
                    ->where(function ($q) {
                        $q->where('total_bet', 0)->orWhereNull('total_bet');
                    })
                    ->delete();
            }

            DB::commit();

            return redirect()->route('wagers.show', $wager->id)
                ->with('success', 'Wager updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wager update failed', [
                'wager_id' => $wager->id,
                'error'    => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Failed to update wager: ' . $e->getMessage());
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
        $user = Auth::user();

        $validated = $request->validate([
            'bets'             => 'required|array|min:1',
            'bets.*.choice_id' => [
                'required',
                'integer',
                Rule::exists('wager_choices', 'id')->where('wager_id', $wager->id),
            ],
            'bets.*.amount' => 'required|integer|min:1',
        ]);

        $totalAmount = collect($validated['bets'])->sum('amount');

        if ($user->balance < $totalAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. You have ' . $user->balance . ' but tried to bet ' . $totalAmount . '.',
            ], 400);
        }

        if ($wager->status === 'ended') {
            return response()->json(['success' => false, 'message' => 'Wager ended'], 400);
        }

        try {
            if (!DB::table('wagers')->where('id', $wager->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Wager not found'], 404);
            }

            $player = DB::table('wager_players')
                ->where('wager_id', $wager->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$player) {
                $playerId = DB::table('wager_players')->insertGetId([
                    'wager_id'   => $wager->id,
                    'user_id'    => $user->id,
                    'bet_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $player = (object) ['id' => $playerId];
            }

            $bets = [];
            foreach ($validated['bets'] as $betData) {
                $choice = DB::table('wager_choices')
                    ->where('wager_id', $wager->id)
                    ->where('id', $betData['choice_id'])
                    ->first();

                $betId = DB::table('wager_bets')->insertGetId([
                    'wager_id'        => $wager->id,
                    'wager_choice_id' => $choice->id,
                    'wager_player_id' => $player->id,
                    'bet_amount'      => $betData['amount'],
                    'amount'          => $betData['amount'],
                    'status'          => 'pending',
                    'actual_payout'   => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                DB::table('wager_choices')
                    ->where('id', $choice->id)
                    ->increment('total_bet', $betData['amount']);

                $bets[] = (object) ['id' => $betId, 'bet_amount' => $betData['amount']];
            }

            DB::table('users')->where('id', $user->id)->decrement('balance', $totalAmount);
            DB::table('wager_players')->where('id', $player->id)->increment('bet_amount', $totalAmount);
            DB::table('wagers')->where('id', $wager->id)->increment('pot', $totalAmount);

            $wager->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Bets placed successfully!',
                'bets'    => $bets,
                'pot'     => $wager->pot,
            ]);

        } catch (\Exception $e) {
            Log::error('BET FAILED', [
                'error'    => $e->getMessage(),
                'wager_id' => $wager->id,
                'user_id'  => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to place bets: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showEndForm(Wager $wager)
    {
        if ($wager->creator_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Only the wager creator can end the wager.'], 403);
            }
            return back()->with('error', 'Only the wager creator can end the wager.');
        }

        if ($wager->status === 'ended') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This wager has already ended.'], 400);
            }
            return back()->with('error', 'This wager has already ended.');
        }

        $playerCount = DB::table('wager_players')->where('wager_id', $wager->id)->count();
        if ($playerCount < 2) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot end a wager with fewer than 2 players.'], 400);
            }
            return back()->with('error', 'Cannot end a wager with fewer than 2 players.');
        }

        $wager->load('choices');

        if ($wager->choices->isEmpty()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No choices available for this wager.'], 400);
            }
            return back()->with('error', 'No choices available for this wager.');
        }

        if (request()->ajax() || request()->wantsJson()) {
            return view('wagers.wagers_end', compact('wager'))->render();
        }

        return view('wagers.wagers_end', compact('wager'));
    }

    public function end(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($wager->status === 'ended') {
            return response()->json(['success' => false, 'message' => 'Wager already ended.'], 400);
        }

        $playerCount = DB::table('wager_players')->where('wager_id', $wager->id)->count();
        if ($playerCount < 2) {
            return response()->json(['success' => false, 'message' => 'Cannot end a wager with fewer than 2 players.'], 400);
        }

        $validated = $request->validate([
            'winning_choice_id' => 'required|integer',
        ]);

        try {
            $winningChoice = DB::table('wager_choices')
                ->where('id', $validated['winning_choice_id'])
                ->where('wager_id', $wager->id)
                ->first();

            if (!$winningChoice) {
                return response()->json(['success' => false, 'message' => 'Invalid choice.'], 400);
            }

            $bets = DB::table('wager_bets as b')
                ->join('wager_players as p', 'b.wager_player_id', '=', 'p.id')
                ->where('b.wager_id', $wager->id)
                ->select('b.id', 'b.bet_amount', 'b.wager_choice_id', 'p.user_id')
                ->get();

            $updated = DB::table('wagers')->where('id', $wager->id)->update([
                'status'            => 'ended',
                'winning_choice_id' => (int) $validated['winning_choice_id'],
                'ended_at'          => now()->toDateTimeString(),
            ]);

            Log::info('Wager update result', ['rows_affected' => $updated]);

            if ($bets->isEmpty()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Wager ended (no bets).',
                    'redirect' => route('wagers.results', $wager->id),
                ]);
            }

            DB::beginTransaction();

            foreach ($bets as $bet) {
                $isWinner = (int) $bet->wager_choice_id === (int) $validated['winning_choice_id'];

                if ($isWinner) {
                    $payout = (int) round($bet->bet_amount * 1.5);
                    DB::table('wager_bets')->where('id', $bet->id)->update([
                        'is_win' => true,
                        'payout' => $payout,
                        'status' => 'won',
                    ]);
                    DB::table('users')->where('id', $bet->user_id)->increment('balance', $payout);
                } else {
                    DB::table('wager_bets')->where('id', $bet->id)->update([
                        'is_win' => false,
                        'payout' => 0,
                        'status' => 'lost',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Wager ended successfully!',
                'redirect' => route('wagers.results', $wager->id),
            ]);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error('Wager end failed', ['wager_id' => $wager->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to end wager: ' . $e->getMessage()], 500);
        }
    }

    public function sendInvitation(Request $request, Wager $wager)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $inviterId = Auth::id();
        $inviteeId = (int) $request->input('friend_id');

        if ($wager->creator_id !== $inviterId && !$wager->hasPlayer($inviterId)) {
            return back()->with('error', 'You must be in this wager to invite others.');
        }

        if ($wager->status === 'ended') {
            return back()->with('error', 'Cannot invite to an ended wager.');
        }

        if ($wager->isFull()) {
            return back()->with('error', 'This wager is already full.');
        }

        if ($wager->hasPlayer($inviteeId)) {
            return back()->with('error', 'This person is already in the wager.');
        }

        $existing = WagerInvitation::where('wager_id', $wager->id)
            ->where('invitee_id', $inviteeId)
            ->where('status', WagerInvitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
            return back()->with('error', 'This person already has a pending invitation.');
        }

        $invitee = \App\Models\User::findOrFail($inviteeId);

        WagerInvitation::create([
            'wager_id'   => $wager->id,
            'inviter_id' => $inviterId,
            'invitee_id' => $inviteeId,
            'email'      => $invitee->email,
            'status'     => WagerInvitation::STATUS_PENDING,
        ]);

        return back()->with('success', 'Invitation sent successfully!');
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

        return redirect()->route('wagers.index')
            ->with('status', 'You have declined the invitation.');
    }

    // ── Other ─────────────────────────────────────────────────────────────────

    public function search(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (empty($query)) {
            return response()->json(['wagers' => []]);
        }

        $wagers = Wager::with(['creator', 'choices'])
            ->where('privacy', 'public')
            ->where('status', '!=', 'ended')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->where('ending_time', '>', now())
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'wagers' => $wagers->map(function ($wager) {
                return [
                    'id'          => $wager->id,
                    'name'        => $wager->name,
                    'description' => $wager->description,
                    'ending_time' => $wager->ending_time,
                    'creator'     => $wager->creator ? $wager->creator->name : 'Unknown',
                    'choices'     => $wager->choices->map(fn($c) => ['id' => $c->id, 'label' => $c->label]),
                ];
            })->toArray(),
        ]);
    }

    public function show(Wager $wager)
    {
        $friends            = Auth::user()->friends;
        $pendingInvitations = $wager->invitations()->where('status', 'pending')->get();
        $isJoined           = $wager->players()->where('user_id', Auth::id())->exists();

        return view('wagers.wager_detail', compact('wager', 'friends', 'pendingInvitations', 'isJoined'));
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
            $userResult = ['name' => $player->name, 'net' => 0];

            foreach ($playerBets as $bet) {
                $isWinner = $bet->wager_choice_id == $winningChoiceId;
                $payout   = $isWinner && $totalWinningBets > 0
                    ? ($bet->bet_amount / $totalWinningBets) * $totalPot
                    : 0;
                $userResult['net'] += $payout - $bet->bet_amount;
            }

            $results[] = $userResult;
        }

        return [
            'winners' => array_values(array_filter($results, fn($r) => $r['net'] > 0)),
            'losers'  => array_values(array_filter($results, fn($r) => $r['net'] <= 0)),
        ];
    }

    public function results(Wager $wager)
    {
        if ($wager->status !== 'ended') {
            return redirect()->route('wagers.show', $wager)->with('error', 'This wager has not ended yet.');
        }

        $wager->load(['winningChoice', 'choices', 'creator', 'players.user', 'bets.wagerChoice']);

        $bets          = $wager->bets()->with(['wagerPlayer.user', 'wagerChoice'])->get();
        $winningChoice = $wager->winningChoice;
        $totalPot      = $wager->players->sum('bet_amount');

        $results = [];
        foreach ($bets->groupBy('wagerPlayer.user_id') as $userId => $userBets) {
            $user       = $userBets->first()->wagerPlayer->user;
            $userResult = ['user' => $user, 'total_bet' => 0, 'payout' => 0, 'profit' => 0, 'status' => 'lost', 'bets' => []];

            foreach ($userBets as $bet) {
                $isWinner = $bet->wager_choice_id === $winningChoice->id;
                $payout   = $isWinner ? $bet->bet_amount * 1.5 : 0;
                $profit   = $payout - $bet->bet_amount;

                $userResult['bets'][]    = ['choice' => $bet->wagerChoice->label, 'amount' => $bet->bet_amount, 'is_winner' => $isWinner, 'payout' => $payout, 'profit' => $profit];
                $userResult['total_bet'] += $bet->bet_amount;
                $userResult['payout']    += $payout;
                $userResult['profit']    += $profit;
                if ($isWinner) $userResult['status'] = 'won';
            }

            $results[] = $userResult;
        }

        return view('wagers.results', [
            'wager'         => $wager,
            'winningChoice' => $winningChoice,
            'results'       => collect($results)->sortByDesc('payout'),
        ]);
    }

    public function stats(Wager $wager)
    {
        $choices  = $wager->choices()->get(['id', 'label', 'total_bet']);
        $totalPot = $wager->pot;

        return response()->json([
            'pot'               => $totalPot,
            'distribution'      => $choices->map(fn($c) => [
                'id'           => $c->id,
                'label'        => $c->label,
                'amount'       => (float) $c->total_bet,
                'total_amount' => (float) $c->total_bet,
                'percentage'   => $totalPot > 0 ? round(($c->total_bet / $totalPot) * 100, 2) : 0,
            ]),
            'status'            => $wager->status,
            'winning_choice_id' => $wager->winning_choice_id,
        ]);
    }
}