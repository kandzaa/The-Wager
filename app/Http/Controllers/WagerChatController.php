<?php

namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WagerChatController extends Controller
{
    // Atgriež derības čata ziņojumus pēc norādītā ID (inkrementāla ielāde)
    public function messages(Request $request, Wager $wager)
    {
        $after = (int) $request->query('after', 0);

        $messages = DB::table('wager_messages as m')
            ->join('users as u', 'm.user_id', '=', 'u.id')
            ->where('m.wager_id', $wager->id)
            ->when($after > 0, fn($q) => $q->where('m.id', '>', $after))
            ->orderBy('m.id')
            ->limit(80)
            ->select('m.id', 'm.message', 'm.created_at', 'u.id as user_id', 'u.name')
            ->get();

        return response()->json($messages);
    }

    // Saglabā jaunu čata ziņojumu derībā (tikai pievienojušies spēlētāji)
    public function store(Request $request, Wager $wager)
    {
        $request->validate(['message' => 'required|string|max:300']);

        $userId = Auth::id();

        $canChat = DB::table('wager_players')
            ->where('wager_id', $wager->id)
            ->where('user_id', $userId)
            ->exists()
            || $wager->creator_id === $userId;

        if (!$canChat) {
            return response()->json(['error' => 'Join the wager to chat.'], 403);
        }

        $id = DB::table('wager_messages')->insertGetId([
            'wager_id'   => $wager->id,
            'user_id'    => $userId,
            'message'    => trim($request->message),
            'created_at' => now(),
        ]);

        $msg = DB::table('wager_messages as m')
            ->join('users as u', 'm.user_id', '=', 'u.id')
            ->where('m.id', $id)
            ->select('m.id', 'm.message', 'm.created_at', 'u.id as user_id', 'u.name')
            ->first();

        return response()->json($msg, 201);
    }
}
