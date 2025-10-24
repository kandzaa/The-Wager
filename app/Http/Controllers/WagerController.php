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
        $wagers     = Wager::where('status', '!=', 'ended')->get();
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

        // FORCE DEBUG - Log EVERYTHING
        \Log::emergency('WAGER DEBUG', [
            'validated_privacy' => $validated['privacy'],
            'calculated_status' => $status,
            'request_all'       => $request->all(),
        ]);

        // MANUAL INSERT - Bypass model mass assignment issues
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

        // Create wager object
        $wager = Wager::find($wagerId);

        foreach ($validated['choices'] as $choice) {
            $wager->choices()->create(['label' => $choice['label']]);
        }

        return response()->json(['message' => 'Wager created successfully', 'id' => $wagerId], 201);
    }

    public function edit(Wager $wager)
    {
        // Check authorization
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to edit this wager.');
        }

        // Don't allow editing ended wagers
        if ($wager->status === 'ended') {
            return back()->with('error', 'Cannot edit an ended wager.');
        }

        // Load choices directly from DB
        $choices = DB::table('wager_choices')
            ->where('wager_id', $wager->id)
            ->orderBy('id')
            ->get();

        $wager->choices = $choices;

        return view('wagers.edit', compact('wager'));
    }

    public function update(Request $request, Wager $wager)
    {
        // Check authorization
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized to update this wager.');
        }

        // Don't allow editing ended wagers
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
            'choices.*.id'    => 'nullable|integer',
            'choices.*.label' => 'required|string|max:255',
        ]);

        try {
            // Get all existing choices BEFORE transaction
            $existingChoices = DB::table('wager_choices')
                ->where('wager_id', $wager->id)
                ->get()
                ->keyBy('id');

            Log::info('STARTING WAGER UPDATE', [
                'wager_id'         => $wager->id,
                'existing_choices' => $existingChoices->count(),
            ]);

            // Start transaction
            DB::beginTransaction();

            // Update wager details - use exact column names
            try {
                DB::statement('SET CONSTRAINTS ALL DEFERRED');

                $affected = DB::table('wagers')
                    ->where('id', $wager->id)
                    ->update([
                        'name'          => $validated['name'],
                        'description'   => $validated['description'],
                        'max_players'   => $validated['max_players'],
                        'privacy'       => $validated['privacy'],
                        'starting_time' => $validated['starting_time'],
                        'ending_time'   => $validated['ending_time'],
                        'updated_at'    => now(),
                    ]);

                Log::info('WAGER TABLE UPDATED', [
                    'wager_id'      => $wager->id,
                    'affected_rows' => $affected,
                ]);

            } catch (\Exception $e) {
                Log::error('WAGER UPDATE FAILED', [
                    'wager_id' => $wager->id,
                    'error'    => $e->getMessage(),
                ]);
                throw $e;
            }

            $processedChoiceIds = [];

            // Process each choice from the form
            foreach ($validated['choices'] as $index => $choiceData) {
                $label = trim($choiceData['label']);

                if (empty($label)) {
                    continue;
                }

                try {
                    if (! empty($choiceData['id']) && isset($existingChoices[$choiceData['id']])) {
                        // Update existing choice
                        $existingChoice = $existingChoices[$choiceData['id']];

                        if ($existingChoice->label !== $label) {
                            $updated = DB::table('wager_choices')
                                ->where('id', $choiceData['id'])
                                ->where('wager_id', $wager->id)
                                ->update([
                                    'label'      => $label,
                                    'updated_at' => now(),
                                ]);

                            Log::info('CHOICE UPDATED', [
                                'choice_id' => $choiceData['id'],
                                'updated'   => $updated,
                            ]);
                        }

                        $processedChoiceIds[] = (int) $choiceData['id'];

                    } else {
                        // Create new choice
                        $choiceId = DB::table('wager_choices')->insertGetId([
                            'wager_id'   => $wager->id,
                            'label'      => $label,
                            'total_bet'  => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $processedChoiceIds[] = $choiceId;

                        Log::info('CHOICE CREATED', [
                            'choice_id' => $choiceId,
                            'label'     => $label,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('CHOICE PROCESSING FAILED', [
                        'index' => $index,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            }

            // Delete choices that were removed (only if they have no bets)
            $choicesToDelete = $existingChoices->keys()->diff($processedChoiceIds);

            if ($choicesToDelete->isNotEmpty()) {
                try {
                    $deletedCount = DB::table('wager_choices')
                        ->where('wager_id', $wager->id)
                        ->whereIn('id', $choicesToDelete->toArray())
                        ->where(function ($query) {
                            $query->where('total_bet', '=', 0)
                                ->orWhereNull('total_bet');
                        })
                        ->delete();

                    Log::info('CHOICES DELETED', [
                        'wager_id' => $wager->id,
                        'deleted'  => $deletedCount,
                    ]);
                } catch (\Exception $e) {
                    Log::error('CHOICE DELETION FAILED', [
                        'error' => $e->getMessage(),
                    ]);
                    // Don't throw - deletion failure is not critical
                }
            }

            DB::commit();

            Log::info('WAGER UPDATE COMPLETED SUCCESSFULLY', ['wager_id' => $wager->id]);

            return redirect()->route('wagers.show', $wager->id)
                ->with('success', 'Wager updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ERROR UPDATING WAGER', [
                'wager_id' => $wager->id,
                'error'    => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => basename($e->getFile()),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update wager. Please check the logs for details.');
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
            'bets'             => 'required|array|min:1',
            'bets.*.choice_id' => 'required|exists:wager_choices,id,wager_id,' . $wager->id,
            'bets.*.amount'    => 'required|integer|min:1|max:' . Auth::user()->balance,
        ]);

        Log::info('BET START', [
            'wager_id'  => $wager->id,
            'user_id'   => Auth::user()->id,
            'bets'      => $validated['bets'],
            'timestamp' => now()->toDateTimeString(),
        ]);

        if ($wager->status === 'ended') {
            Log::warning('Wager ended', ['wager_id' => $wager->id]);
            return response()->json(['success' => false, 'message' => 'Wager ended'], 400);
        }

        $user        = Auth::user();
        $totalAmount = collect($validated['bets'])->sum('amount');

        if ($user->balance < $totalAmount) {
            Log::warning('Insufficient balance', [
                'user_id'      => $user->id,
                'balance'      => $user->balance,
                'total_amount' => $totalAmount,
            ]);
            return response()->json(['success' => false, 'message' => 'Insufficient balance'], 400);
        }

        try {
            // Check data
            Log::info('CHECK WAGER', ['wager_id' => $wager->id]);
            if (! DB::table('wagers')->where('id', $wager->id)->exists()) {
                Log::error('Wager not found', ['wager_id' => $wager->id]);
                return response()->json(['success' => false, 'message' => 'Wager not found'], 404);
            }

            Log::info('CHECK USER', ['user_id' => $user->id]);
            if (! DB::table('users')->where('id', $user->id)->exists()) {
                Log::error('User not found', ['user_id' => $user->id]);
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            foreach ($validated['bets'] as $betData) {
                Log::info('CHECK CHOICE', ['choice_id' => $betData['choice_id']]);
                if (! DB::table('wager_choices')->where('wager_id', $wager->id)->where('id', $betData['choice_id'])->exists()) {
                    Log::error('Choice not found', ['choice_id' => $betData['choice_id']]);
                    return response()->json(['success' => false, 'message' => 'Choice not found'], 404);
                }
            }

            // Get or create player
            Log::info('CHECK PLAYER', ['wager_id' => $wager->id, 'user_id' => $user->id]);
            $player = DB::table('wager_players')
                ->where('wager_id', $wager->id)
                ->where('user_id', $user->id)
                ->first();

            if (! $player) {
                Log::info('INSERT PLAYER', ['wager_id' => $wager->id, 'user_id' => $user->id]);
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
                Log::info('INSERT BET', ['choice_id' => $betData['choice_id'], 'amount' => $betData['amount']]);
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

                Log::info('UPDATE CHOICE TOTAL', ['choice_id' => $choice->id]);
                DB::table('wager_choices')
                    ->where('id', $choice->id)
                    ->increment('total_bet', $betData['amount']);

                $bets[] = (object) ['id' => $betId, 'bet_amount' => $betData['amount']];
            }

            Log::info('UPDATE BALANCES', ['total_amount' => $totalAmount]);
            DB::table('users')->where('id', $user->id)->decrement('balance', $totalAmount);
            DB::table('wager_players')->where('id', $player->id)->increment('bet_amount', $totalAmount);
            DB::table('wagers')->where('id', $wager->id)->increment('pot', $totalAmount);

            Log::info('BET SUCCESS', ['wager_id' => $wager->id]);
            $user->refresh();
            $wager->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Bets placed successfully!',
                'bets'    => $bets,
                'pot'     => $wager->pot,
            ]);

        } catch (\Exception $e) {
            Log::error('BET FAILED', [
                'error'     => $e->getMessage(),
                'wager_id'  => $wager->id,
                'user_id'   => $user->id,
                'bets'      => $validated['bets'],
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to place bets: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showEndForm(Wager $wager)
    {
        // Check authorization
        if ($wager->creator_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the wager creator can end the wager.',
                ], 403);
            }
            return back()->with('error', 'Only the wager creator can end the wager.');
        }

        // Check if already ended
        if ($wager->status === 'ended') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This wager has already ended.',
                ], 400);
            }
            return back()->with('error', 'This wager has already ended.');
        }

        // Load choices using raw query to ensure they exist
        $choices = DB::table('wager_choices')
            ->where('wager_id', $wager->id)
            ->orderBy('id')
            ->get();

        if ($choices->isEmpty()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No choices available for this wager.',
                ], 400);
            }
            return back()->with('error', 'No choices available for this wager.');
        }

        // Convert to collection for the view
        $wager->choices = $choices;

        Log::info('SHOWING END FORM', [
            'wager_id'      => $wager->id,
            'choices_count' => $choices->count(),
        ]);

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
            'winning_choice_id' => 'required|integer',
        ]);

        try {
            // Verify the choice exists and belongs to this wager BEFORE transaction
            $winningChoice = DB::table('wager_choices')
                ->where('id', $validated['winning_choice_id'])
                ->where('wager_id', $wager->id)
                ->first();

            if (! $winningChoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid winning choice for this wager.',
                ], 400);
            }

            // Get all bets BEFORE transaction
            $bets = DB::table('wager_bets')
                ->where('wager_id', $wager->id)
                ->get();

            Log::info('ENDING WAGER', [
                'wager_id'          => $wager->id,
                'winning_choice_id' => $validated['winning_choice_id'],
                'total_bets'        => $bets->count(),
            ]);

            if ($bets->isEmpty()) {
                // No bets, just mark as ended
                DB::table('wagers')
                    ->where('id', $wager->id)
                    ->update([
                        'status'            => 'ended',
                        'winning_choice_id' => $validated['winning_choice_id'],
                        'updated_at'        => now(),
                    ]);

                return response()->json([
                    'success'  => true,
                    'message'  => 'Wager ended successfully (no bets to process).',
                    'redirect' => route('history.wager.show', $wager->id),
                ]);
            }

            // Get all players BEFORE transaction
            $playerIds = $bets->pluck('wager_player_id')->unique();
            $players   = DB::table('wager_players')
                ->whereIn('id', $playerIds)
                ->get()
                ->keyBy('id');

            Log::info('LOADED DATA', [
                'bets'    => $bets->count(),
                'players' => $players->count(),
            ]);

            DB::beginTransaction();

            try {
                DB::statement('SET CONSTRAINTS ALL DEFERRED');

                // Update wager status
                $updated = DB::table('wagers')
                    ->where('id', $wager->id)
                    ->update([
                        'status'            => 'ended',
                        'winning_choice_id' => $validated['winning_choice_id'],
                        'updated_at'        => now(),
                    ]);

                Log::info('WAGER STATUS UPDATED', ['affected' => $updated]);

                // 1.5x payout for winners
                $payoutMultiplier = 1.5;
                $winnersCount     = 0;
                $losersCount      = 0;

                foreach ($bets as $bet) {
                    $isWinner = $bet->wager_choice_id == $validated['winning_choice_id'];

                    if ($isWinner) {
                        $payout = $bet->bet_amount * $payoutMultiplier;

                        // Get player to find user
                        if (isset($players[$bet->wager_player_id])) {
                            $player = $players[$bet->wager_player_id];

                            // Credit user's balance
                            DB::table('users')
                                ->where('id', $player->user_id)
                                ->increment('balance', $payout);

                            $winnersCount++;

                            Log::info('WINNER CREDITED', [
                                'user_id' => $player->user_id,
                                'payout'  => $payout,
                            ]);
                        }

                        // Update bet record
                        DB::table('wager_bets')
                            ->where('id', $bet->id)
                            ->update([
                                'is_win'     => true,
                                'payout'     => $payout,
                                'updated_at' => now(),
                            ]);
                    } else {
                        // Mark as loser
                        DB::table('wager_bets')
                            ->where('id', $bet->id)
                            ->update([
                                'is_win'     => false,
                                'payout'     => 0,
                                'updated_at' => now(),
                            ]);

                        $losersCount++;
                    }
                }

                DB::commit();

                Log::info('WAGER ENDED SUCCESSFULLY', [
                    'wager_id' => $wager->id,
                    'winners'  => $winnersCount,
                    'losers'   => $losersCount,
                ]);

                return response()->json([
                    'success'  => true,
                    'message'  => 'Wager ended successfully!',
                    'redirect' => route('history.wager.show', $wager->id),
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('ERROR ENDING WAGER', [
                'wager_id' => $wager->id,
                'error'    => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => basename($e->getFile()),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to end wager. Please try again.',
            ], 500);
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
