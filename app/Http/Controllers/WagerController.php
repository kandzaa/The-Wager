<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use App\Services\WagerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WagerController extends Controller
{
    private WagerService $wagerService;

    public function __construct(WagerService $wagerService)
    {
        $this->wagerService = $wagerService;
    }

    public function index()
    {
        try {
            $wagers = Wager::with(['creator', 'choices'])
                ->where(function ($query) {
                    $query->where('status', 'public')
                        ->orWhere('creator_id', Auth::id() ?? 0);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $friends = Auth::user() ? Auth::user()->friends()->get() : collect();

            return view('wagers.lobby', compact('wagers', 'friends'));
        } catch (Exception $e) {
            Log::error('Error loading wagers index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load wagers. Please try again.');
        }
    }

    public function stats(Wager $wager)
    {
        try {
            return response()->json($this->wagerService->getWagerStats($wager));
        } catch (Exception $e) {
            Log::error('Error fetching wager stats: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to load wager statistics.',
            ], 500);
        }
    }

    public function bet(Request $request, Wager $wager)
    {
        try {
            // Validācija derībām
            $validated = $request->validate([
                'choice_id' => 'required|integer|exists:wager_choices,id',
                'amount'    => 'required|integer|min:1|max:10000',
            ], [
                'choice_id.required' => 'Please select a betting choice.',
                'choice_id.exists'   => 'Selected choice is no longer valid.',
                'amount.required'    => 'Bet amount is required.',
                'amount.integer'     => 'Bet amount must be a valid number.',
                'amount.min'         => 'Minimum bet is 1 credit.',
                'amount.max'         => 'Maximum bet is 10,000 credits per wager.',
            ]);

            // vai derības vēl pastāv?
            if ($wager->ending_time <= now()) {
                return redirect()->back()->with('error', 'This wager has already ended.');
            }

            if ($wager->status !== 'public' && $wager->creator_id !== Auth::id()) {
                return redirect()->back()->with('error', 'This wager is not available for betting.');
            }

            // vai lietotājs ir derībās
            $players     = collect($wager->players ?? []);
            $userInWager = $players->contains(function ($p) {
                return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
            });

            if (! $userInWager) {
                return redirect()->back()->with('error', 'Please join this wager before placing a bet.');
            }

            // kuras izvēles lietotājs izvēlējās
            $choice = $wager->choices()->where('id', $validated['choice_id'])->first();
            if (! $choice) {
                return redirect()->back()->with('error', 'Invalid choice selected for this wager.');
            }

            // vai lietotājs jau ir derējis
            $existingBet = $players->first(function ($p) {
                return is_array($p) &&
                ($p['user_id'] ?? null) === Auth::id() &&
                ($p['amount'] ?? 0) > 0;
            });

            if ($existingBet) {
                return redirect()->back()->with('error', 'You have already placed a bet on this wager.');
            }

            DB::beginTransaction();

            try {
                $amount = (int) $validated['amount'];
                $user   = Auth::user();
                $user->refresh();

                // cik spēlētājam naudas
                if ((int) ($user->balance ?? 0) < $amount) {
                    throw new Exception('Insufficient balance to place this bet.');
                }

                // noņem naudu
                $user->balance = (int) $user->balance - $amount;
                $user->save();

                // pieliec pie "pot"
                $wager->pot = (int) $wager->pot + $amount;

                // atjaunini spēlētāja derību
                $updatedPlayers = $players->map(function ($p) use ($amount, $choice, $user) {
                    if (is_array($p) && ($p['user_id'] ?? null) === Auth::id()) {
                        $p['choice'] = $choice->label;
                        $p['amount'] = $amount;
                        $p['time']   = now()->toDateTimeString();
                    }
                    return $p;
                })->toArray();

                $wager->players = $updatedPlayers;
                $wager->save();

                $choice->total_bet = (int) $choice->total_bet + $amount;
                $choice->save();

                Log::info('Bet placed successfully', [
                    'user_id'   => $user->id,
                    'wager_id'  => $wager->id,
                    'choice_id' => $choice->id,
                    'amount'    => $amount,
                ]);

                DB::commit();

                return redirect()->route('wager.show', ['id' => $wager->id])
                    ->with('success', 'Bet placed successfully! Good luck!');

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            Log::error('Error placing bet: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to place bet: ' . $e->getMessage());
        }
    }

    public function join(Wager $wager)
    {
        try {
            if ($wager->ending_time <= now()) {
                return redirect()->back()->with('error', 'This wager has already ended.');
            }

            $currentPlayers = collect($wager->players ?? [])->count();
            if ($currentPlayers >= $wager->max_players) {
                return redirect()->back()->with('error', 'This wager is full.');
            }

            $players       = collect($wager->players ?? []);
            $alreadyJoined = $players->contains(function ($p) {
                return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
            });

            if ($alreadyJoined) {
                return redirect()->route('wager.show', ['id' => $wager->id])
                    ->with('info', 'You are already part of this wager.');
            }

            $newPlayer = [
                'user_id' => Auth::id(),
                'name'    => Auth::user()->name,
                'choice'  => null,
                'amount'  => 0,
                'joined'  => true,
                'time'    => now()->toDateTimeString(),
            ];

            $players->push($newPlayer);
            $wager->players = $players->toArray();
            $wager->save();

            return redirect()->route('wager.show', ['id' => $wager->id])
                ->with('success', 'Successfully joined the wager!');

        } catch (Exception $e) {
            Log::error('Error joining wager: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to join wager. Please try again.');
        }
    }

    public function create(Request $request)
    {
        try {
            $this->wagerService->validateUserPermissions();

            $validated = $request->validate(
                $this->wagerService->getValidationRules(),
                $this->wagerService->getValidationMessages()
            );
            $this->wagerService->validateWagerData($validated);

            $wager = $this->wagerService->createWager($validated, Auth::user());

            return redirect()->route('wagers')
                ->with('success', 'Wager created successfully! You have been automatically joined.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        } catch (Exception $e) {
            Log::error('Error creating wager: ' . $e->getMessage(), [
                'user_id'      => Auth::id() ?? 'guest',
                'request_data' => $request->except(['_token']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function showWager($id)
    {
        try {
            $wager = Wager::with(['creator', 'choices'])
                ->findOrFail($id);

            if ($wager->status === 'private' && $wager->creator_id !== Auth::id()) {
                $players     = collect($wager->players ?? []);
                $userInWager = $players->contains(function ($p) {
                    return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
                });

                if (! $userInWager) {
                    abort(403, 'This is a private wager.');
                }
            }

            $friends = Auth::user() ? Auth::user()->friends()->get() : collect();

            return view('wagers.wager_detail', compact('wager', 'friends'));
        } catch (Exception $e) {
            Log::error('Error showing wager: ' . $e->getMessage());
            return redirect()->route('wagers')->with('error', 'Wager not found or access denied.');
        }
    }

    public function search(Request $request)
    {
        try {
            $query = trim((string) $request->query('query', ''));

            if (strlen($query) < 2) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Search query must be at least 2 characters long.',
                ], 400);
            }

            $wagers = Wager::with('choices')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->where(function ($q) {
                    $q->where('status', 'public')
                        ->orWhere('creator_id', Auth::id() ?? 0);
                })
                ->where('ending_time', '>', now())
                ->limit(20)
                ->get();

            return response()->json([
                'status' => 'success',
                'data'   => $wagers->map(function ($wager) {
                    return [
                        'id'              => $wager->id,
                        'name'            => $wager->name,
                        'description'     => $wager->description,
                        'status'          => $wager->status,
                        'max_players'     => $wager->max_players,
                        'current_players' => count($wager->players ?? []),
                        'pot'             => $wager->pot,
                        'ends_human'      => optional($wager->ending_time)->diffForHumans(),
                        'choices'         => $wager->choices->map(fn($c) => [
                            'id'        => $c->id,
                            'label'     => $c->label,
                            'total_bet' => $c->total_bet,
                        ]),
                    ];
                })
            ]);
        } catch (Exception $e) {
            Log::error('Error in wager search: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Search failed. Please try again.',
            ], 500);
        }
    }

    public function update(Request $request, Wager $wager)
    {
        try {
            if ($wager->creator_id !== Auth::id()) {
                abort(403, 'You can only edit your own wagers.');
            }

            if ($wager->ending_time <= now()) {
                return redirect()->back()->with('error', 'Cannot edit a wager that has already ended.');
            }

            $totalBets = collect($wager->players ?? [])->sum('amount');
            if ($totalBets > 0) {
                return redirect()->back()->with('error', 'Cannot edit a wager that already has bets placed.');
            }

            $validated = $request->validate(
                $this->wagerService->getValidationRules(),
                $this->wagerService->getValidationMessages()
            );

            $wager = $this->wagerService->updateWager($wager, $validated);

            return redirect()->route('wagers')
                ->with('success', 'Wager updated successfully!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            Log::error('Error updating wager: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Wager $wager)
    {
        try {
            if ($wager->creator_id !== Auth::id()) {
                abort(403, 'You can only delete your own wagers.');
            }

            $this->wagerService->deleteWager($wager);

            return redirect()->route('wagers')
                ->with('success', 'Wager deleted and all bets have been refunded.');

        } catch (Exception $e) {
            Log::error('Error deleting wager: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete wager. Please try again.');
        }
    }
}
