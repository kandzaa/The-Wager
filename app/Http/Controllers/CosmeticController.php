<?php

namespace App\Http\Controllers;

use App\Models\Cosmetic;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            $freshUser = DB::table('users')
                ->where('id', $user->id)
                ->lockForUpdate()
                ->first();

            $alreadyOwned = DB::table('user_cosmetics')
                ->where('user_id', $user->id)
                ->where('cosmetic_id', $cosmetic->id)
                ->exists();

            if ($alreadyOwned) {
                throw new HttpResponseException(
                    response()->json(['success' => false, 'message' => 'Already owned.'], 400)
                );
            }

            if ($freshUser->balance < $cosmetic->price) {
                throw new HttpResponseException(
                    response()->json(['success' => false, 'message' => 'Not enough coins.'], 400)
                );
            }

            DB::table('users')
                ->where('id', $user->id)
                ->decrement('balance', $cosmetic->price);

            DB::table('user_cosmetics')->insert([
                'user_id'     => $user->id,
                'cosmetic_id' => $cosmetic->id,
                'created_at'  => now(),
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

        DB::transaction(function () use ($user, $cosmetic, $request) {
            DB::table('user_equipped')
                ->where('user_id', $user->id)
                ->where('slot', $request->slot)
                ->delete();

            DB::table('user_equipped')->insert([
                'user_id'     => $user->id,
                'slot'        => $request->slot,
                'cosmetic_id' => $cosmetic->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => "'{$cosmetic->name}' equipped!"]);
    }
}