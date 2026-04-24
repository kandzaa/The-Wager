<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoinflipController extends Controller
{
    // Atgriež monētas mešanas lapu ar pēdējo 10 spēļu vēsturi
    public function index()
    {
        $history = DB::table('coinflip_games')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('coinflip', compact('history'));
    }

    // Apstrādā monētas mešanu un atjaunina lietotāja bilanci
    public function flip(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:2147483647',
            'pick'   => 'required|in:heads,tails',
        ]);

        $user   = Auth::user();
        $amount = (int) $request->amount;
        $pick   = $request->pick;

        if ($user->balance < $amount) {
            return response()->json(['error' => 'Not enough coins.'], 422);
        }

        $result = rand(0, 1) ? 'heads' : 'tails';
        $won    = $pick === $result;
        $payout = $won ? $amount : -$amount;

        DB::transaction(function () use ($user, $amount, $won, $pick, $result, $payout) {
            if ($won) {
                DB::table('users')->where('id', $user->id)->increment('balance', $amount);
            } else {
                DB::table('users')->where('id', $user->id)->decrement('balance', $amount);
            }

            DB::table('coinflip_games')->insert([
                'user_id'    => $user->id,
                'amount'     => $amount,
                'pick'       => $pick,
                'result'     => $result,
                'won'        => $won,
                'payout'     => $payout,
                'created_at' => now(),
            ]);
        });

        return response()->json([
            'result'      => $result,
            'won'         => $won,
            'amount'      => $amount,
            'payout'      => $payout,
            'new_balance' => DB::table('users')->where('id', $user->id)->value('balance'),
        ]);
    }
}
