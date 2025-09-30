<?php
namespace App\Services;

use App\Models\User;
use App\Models\Wager;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WagerService
{
    /**
     * Get validation rules for wager creation/updating
     */
    public function getValidationRules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_players' => 'required|integer|min:2|max:100',
            'ending_time' => 'required|date|after:now',
            'status'      => 'required|in:public,private',
            'choices'     => 'required|array|min:2|max:10',
            'choices.*'   => 'required|string|max:255|distinct',
        ];
    }

    public function getValidationMessages(): array
    {
        return [
            'name.required'        => 'A theme name is required for your wager.',
            'name.min'             => 'Theme name must be at least 3 characters long.',
            'name.max'             => 'Theme name cannot exceed 255 characters.',
            'name.regex'           => 'Theme name contains invalid characters. Only letters, numbers, spaces, and basic punctuation are allowed.',
            'description.max'      => 'Description cannot exceed 1000 characters.',
            'max_players.required' => 'Maximum number of players is required.',
            'max_players.integer'  => 'Maximum players must be a valid number.',
            'max_players.min'      => 'At least 2 players are required for a wager.',
            'max_players.max'      => 'Maximum of 100 players allowed per wager.',
            'visibility.required'  => 'Wager visibility setting is required.',
            'visibility.in'        => 'Visibility must be either public or private.',
            'ending_time.after'    => 'Wager must end at least 1 hour from now.',
            'ending_time.before'   => 'Wager cannot be scheduled more than 1 year in advance.',
            'choices.required'     => 'At least one choice is required for betting.',
            'choices.min'          => 'You must provide at least one betting choice.',
            'choices.max'          => 'Maximum of 10 betting choices allowed.',
            'choices.*.required'   => 'All choice fields must be filled out.',
            'choices.*.min'        => 'Each choice must have at least 1 character.',
            'choices.*.max'        => 'Each choice cannot exceed 255 characters.',
            'choices.*.distinct'   => 'All betting choices must be unique.',
        ];
    }

    /**
     * Validate user permissions for wager operations
     */
    public function validateUserPermissions(): void
    {
        if (! Auth::check()) {
            throw new Exception('You must be logged in to create wagers.', 401);
        }

        $user = Auth::user();
        if (! $user) {
            throw new Exception('User authentication failed.', 401);
        }
    }

    /**
     * Validate wager data and choices
     */
    public function validateWagerData(array $validated): void
    {

        $endTime = Carbon::parse($validated['ending_time']);
        $now     = now();

        if ($endTime->lessThanOrEqualTo($now->addHour())) {
            throw new Exception('Wager must end at least 1 hour from now to allow for betting.', 422);
        }

        if ($endTime->greaterThan($now->addYear())) {
            throw new Exception('Wager cannot be scheduled more than 1 year in advance.', 422);
        }

        $choices = collect($validated['choices'])
            ->map(fn($choice) => trim($choice))
            ->filter(fn($choice) => ! empty($choice));

        if ($choices->count() < 1) {
            throw new Exception('At least one valid betting choice is required.', 422);
        }

        // Check for duplicates (case-insensitive)
        $lowerChoices = $choices->map(fn($choice) => strtolower($choice));
        if ($lowerChoices->count() !== $lowerChoices->unique()->count()) {
            throw new Exception('All betting choices must be unique (case-insensitive).', 422);
        }

                                                                 // Basic content filtering for inappropriate choices
        $inappropriateWords = ['spam', 'scam', 'hack', 'cheat']; // Add more as needed
        foreach ($choices as $choice) {
            foreach ($inappropriateWords as $word) {
                if (stripos($choice, $word) !== false) {
                    throw new Exception("Choice contains inappropriate content: {$word}", 422);
                }
            }
        }
    }

    /**
     * Create a new wager
     */
    public function createWager(array $validated, User $user): Wager
    {
        DB::beginTransaction();

        try {
            // Create the wager
            $wager                = new Wager();
            $wager->name          = $validated['name'];
            $wager->creator_id    = $user->id;
            $wager->description   = $validated['description'] ?? '';
            $wager->max_players   = $validated['max_players'];
            $wager->status        = $validated['visibility'] === 'public' ? 'public' : 'private';
            $wager->players       = json_encode([]);
            $wager->game_history  = json_encode([]);
            $wager->starting_time = now();
            $wager->ending_time   = Carbon::parse($validated['ending_time']);
            $wager->pot           = 0;
            $wager->save();

            // Create choices
            $choices = collect($validated['choices'])
                ->filter(fn($choice) => ! empty(trim($choice)))
                ->map(fn($choice) => [
                    'label'      => trim($choice),
                    'total_bet'  => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            if ($choices->isNotEmpty()) {
                $wager->choices()->createMany($choices->toArray());
            }

            // Log successful creation
            Log::info('Wager created successfully', [
                'user_id'  => $user->id,
                'wager_id' => $wager->id,
                'name'     => $wager->name,
            ]);

            DB::commit();
            return $wager;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating wager: ' . $e->getMessage(), [
                'user_id'      => $user->id,
                'request_data' => array_diff_key($validated, array_flip(['password'])),
            ]);
            throw $e;
        }
    }

    public function updateWager(Wager $wager, array $validated): Wager
    {
        DB::beginTransaction();

        try {
            // Update wager
            $wager->name        = $validated['name'];
            $wager->description = $validated['description'] ?? '';
            $wager->max_players = $validated['max_players'];
            $wager->status      = $validated['visibility'] === 'public' ? 'public' : 'private';
            $wager->ending_time = Carbon::parse($validated['ending_time']);
            $wager->save();

            // Update choices
            $choices = collect($validated['choices'])
                ->filter(fn($choice) => ! empty(trim($choice)))
                ->map(fn($choice) => [
                    'label'     => trim($choice),
                    'total_bet' => 0,
                ]);

            $wager->choices()->delete();
            if ($choices->isNotEmpty()) {
                $wager->choices()->createMany($choices->toArray());
            }

            Log::info('Wager updated successfully', [
                'user_id'  => Auth::id(),
                'wager_id' => $wager->id,
            ]);

            DB::commit();
            return $wager;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a wager with refunds
     */
    public function deleteWager(Wager $wager): void
    {
        DB::beginTransaction();

        try {
            // Check if wager has active bets
            $players       = collect($wager->players ?? []);
            $hasActiveBets = $players->some(function ($player) {
                return is_array($player) && ($player['amount'] ?? 0) > 0;
            });

            if ($hasActiveBets) {
                // Refund all players
                $players = $players->map(function ($player) {
                    if (is_array($player) && ($player['amount'] ?? 0) > 0) {
                        $user = User::find($player['user_id']);
                        if ($user) {
                            $user->balance += $player['amount'];
                            $user->save();
                        }
                        $player['amount'] = 0;
                    }
                    return $player;
                })->toArray();

                $wager->players = $players;
                $wager->pot     = 0;
                $wager->save();
            }

            $wager->delete();

            Log::info('Wager deleted with refunds', [
                'user_id'  => Auth::id(),
                'wager_id' => $wager->id,
            ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get wager statistics for chart
     */
    public function getWagerStats(Wager $wager): array
    {
        try {
            $wager->load('choices');

            return [
                'labels' => $wager->choices->pluck('label')->values(),
                'data'   => $wager->choices->pluck('total_bet')->map(fn($v) => (int) $v)->values(),
                'status' => 'success',
            ];
        } catch (Exception $e) {
            Log::error('Error fetching wager stats: ' . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Unable to load wager statistics.',
            ];
        }
    }
}
