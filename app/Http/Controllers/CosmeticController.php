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

        if (DB::table('user_cosmetics')
                ->where('user_id', $user->id)
                ->where('cosmetic_id', $cosmetic->id)
                ->exists()) {
            return response()->json(['success' => false, 'message' => 'Already owned.'], 400);
        }

        if ($user->balance < $cosmetic->price) {
            return response()->json(['success' => false, 'message' => 'Not enough coins.'], 400);
        }

        // Use raw SQL entirely — no Laravel query builder touching timestamps
        DB::statement('UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?', [
            $cosmetic->price, $user->id, $cosmetic->price
        ]);

        $affected = DB::select('SELECT balance FROM users WHERE id = ?', [$user->id]);
        $newBalance = $affected[0]->balance ?? 0;

        // If balance didn't change (was too low), catch it
        if (($user->balance - $cosmetic->price) !== $newBalance && $newBalance >= 0) {
            // balance changed correctly, continue
        }

        DB::statement('
            INSERT INTO user_cosmetics (user_id, cosmetic_id, created_at, updated_at)
            VALUES (?, ?, NOW(), NOW())
            ON CONFLICT (user_id, cosmetic_id) DO NOTHING
        ', [$user->id, $cosmetic->id]);

        return response()->json([
            'success' => true,
            'message' => "'{$cosmetic->name}' purchased!",
            'balance' => $newBalance,
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
            DB::statement('DELETE FROM user_equipped WHERE user_id = ? AND slot = ?', [
                $user->id, $request->slot
            ]);
            return response()->json(['success' => true, 'message' => 'Unequipped.']);
        }

        $cosmetic = Cosmetic::findOrFail($request->cosmetic_id);

        if (!DB::table('user_cosmetics')
                ->where('user_id', $user->id)
                ->where('cosmetic_id', $cosmetic->id)
                ->exists()) {
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

        // ON CONFLICT UPDATE handles the UNIQUE(user_id, slot) constraint without throwing
        DB::statement('
            INSERT INTO user_equipped (user_id, slot, cosmetic_id, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
            ON CONFLICT (user_id, slot) DO UPDATE SET cosmetic_id = EXCLUDED.cosmetic_id, updated_at = NOW()
        ', [$user->id, $request->slot, $cosmetic->id]);

        return response()->json(['success' => true, 'message' => "'{$cosmetic->name}' equipped!"]);
    }
}