<?php

namespace App\Http\Controllers;

use App\Models\Cosmetic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CosmeticController extends Controller
{
    public function buy(Request $request)
    {
        $request->validate(['cosmetic_id' => 'required|integer|exists:cosmetics,id']);

        $user     = Auth::user();
        $cosmetic = Cosmetic::findOrFail($request->cosmetic_id);

        DB::transaction(function () use ($user, $cosmetic) {

            // Lock the user row to prevent race conditions
            $freshUser = DB::table('users')
                ->where('id', $user->id)
                ->lockForUpdate()
                ->first();

            // Re-check ownership inside the transaction
            $alreadyOwned = DB::table('user_cosmetics')
                ->where('user_id', $user->id)
                ->where('cosmetic_id', $cosmetic->id)
                ->exists();

            if ($alreadyOwned) {
                abort(400, 'Already owned.');
            }

            if ($freshUser->balance < $cosmetic->price) {
                abort(400, 'Not enough coins.');
            }

            DB::table('users')
                ->where('id', $user->id)
                ->decrement('balance', $cosmetic->price);

            DB::table('user_cosmetics')->insert([
                'user_id'     => $user->id,
                'cosmetic_id' => $cosmetic->id,
                'created_at'  => now(), // ✅ use now(), not date()
                'updated_at'  => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "'{$cosmetic->name}' purchased!",
            'balance' => $user->fresh()->balance,
        ]);
    }

    public function equip(Request $request)
    {
        $request->validate([
            'slot'        => 'required|string|in:frame,title,theme,charm_1,charm_2,charm_3',
            'cosmetic_id' => 'nullable|integer|exists:cosmetics,id',
        ]);

        $user = Auth::user();

        if (!$request->cosmetic_id) {
            DB::table('user_equipped')
                ->where('user_id', $user->id)
                ->where('slot', $request->slot)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Unequipped.']);
        }

        $cosmetic = Cosmetic::findOrFail($request->cosmetic_id);

        $owned = DB::table('user_cosmetics')
            ->where('user_id', $user->id)
            ->where('cosmetic_id', $cosmetic->id)
            ->exists();

        if (!$owned) {
            return response()->json(['success' => false, 'message' => 'You do not own this.'], 403);
        }

        $slotTypeMap = [
            'frame'   => 'frame',
            'title'   => 'title',
            'theme'   => 'theme',
            'charm_1' => 'charm',
            'charm_2' => 'charm',
            'charm_3' => 'charm',
        ];

        if ($slotTypeMap[$request->slot] !== $cosmetic->type) {
            return response()->json(['success' => false, 'message' => 'Wrong slot type.'], 400);
        }

        // Wrap delete+insert atomically to prevent partial writes
        DB::transaction(function () use ($user, $cosmetic, $request) {
            DB::table('user_equipped')
                ->where('user_id', $user->id)
                ->where('slot', $request->slot)
                ->delete();

            DB::table('user_equipped')->insert([
                'user_id'     => $user->id,
                'slot'        => $request->slot,
                'cosmetic_id' => $cosmetic->id,
                'created_at'  => now(), // ✅ use now()
                'updated_at'  => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => "'{$cosmetic->name}' equipped!"]);
    }
}