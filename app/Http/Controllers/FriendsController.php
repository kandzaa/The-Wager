<?php
namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index()
    {
        $friends          = Auth::user()->friends()->get();
        $incomingRequests = FriendRequest::with('requester')
            ->where('recipient_id', Auth::id())
            ->where('status', 'pending')
            ->get();
        return view('Friends.friends', compact('friends', 'incomingRequests'));
    }

    public function searchUsers(Request $request)
    {
        $query = trim($request->get('query', ''));

        if (empty($query)) {
            return response()->json([]);
        }

        $user = Auth::user();

        $friendIds  = $user->friends()->pluck('friend_id')->toArray();
        $excludeIds = array_merge($friendIds, [$user->id]);
        $lower      = strtolower($query);

        $users = User::whereNotIn('id', $excludeIds)
            ->where(function ($q) use ($lower) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$lower}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$lower}%"]);
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'created_at']);

        return response()->json($users->map(function ($user) {
            return [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'joined'  => $user->created_at->diffForHumans(),
                'initial' => strtoupper(substr($user->name, 0, 1)),
            ];
        }));
    }

    public function listFriends()
    {
        $friends = Auth::user()->friends()->get();

        return response()->json([
            'success' => true,
            'friends' => $friends->map(function ($friend) {
                return [
                    'id'      => $friend->id,
                    'name'    => $friend->name,
                    'email'   => $friend->email,
                    'initial' => strtoupper(substr($friend->name, 0, 1)),
                ];
            }),
        ]);
    }

    public function addFriend(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:users,id']);

        $friendId = $request->input('friend_id');
        $user     = Auth::user();

        if ($user->id == $friendId) {
            return response()->json(['message' => 'You cannot add yourself as a friend.'], 400);
        }

        if ($user->friends()->where('friend_id', $friendId)->exists()) {
            return response()->json(['message' => 'This user is already your friend.'], 400);
        }

        try {
            $user->friends()->attach($friendId);
            return response()->json(['message' => 'Friend added successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error adding friend.'], 500);
        }
    }

    public function showUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->route('friends')->with('error', 'User not found.');
        }

        return view('Friends.user-show', compact('user'));
    }

    public function removeFriend(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:users,id']);

        $friendId = $request->input('friend_id');
        $user     = Auth::user();

        if ($user->friends()->where('friend_id', $friendId)->exists()) {
            $user->friends()->detach($friendId);
            $friend = User::find($friendId);
            if ($friend) {
                $friend->friends()->detach($user->id);
            }
            return response()->json(['message' => 'Friend removed successfully.']);
        }

        return response()->json(['message' => 'This user is not your friend.'], 400);
    }

    public function requestFriend(Request $request)
    {
        $request->validate(['recipient_id' => 'required|exists:users,id']);

        $recipientId = (int) $request->input('recipient_id');
        $userId      = (int) Auth::id();

        if ($recipientId === $userId) {
            return response()->json(['message' => 'You cannot send a request to yourself.'], 400);
        }

        if (Auth::user()->friends()->where('friend_id', $recipientId)->exists()) {
            return response()->json(['message' => 'You are already friends.'], 400);
        }

        $existing = FriendRequest::where(function ($q) use ($userId, $recipientId) {
            $q->where('requester_id', $userId)->where('recipient_id', $recipientId);
        })->orWhere(function ($q) use ($userId, $recipientId) {
            $q->where('requester_id', $recipientId)->where('recipient_id', $userId);
        })->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return response()->json(['message' => 'A request is already pending.'], 400);
            }
            $existing->status = 'pending';
            $existing->save();
            return response()->json(['message' => 'Friend request re-sent.']);
        }

        FriendRequest::create([
            'requester_id' => $userId,
            'recipient_id' => $recipientId,
            'status'       => 'pending',
        ]);

        return response()->json(['message' => 'Friend request sent.']);
    }

    public function acceptRequest(Request $request)
    {
        $request->validate(['request_id' => 'required|exists:friend_requests,id']);

        $friendRequest = FriendRequest::with(['requester', 'recipient'])->findOrFail($request->input('request_id'));

        if ($friendRequest->recipient_id !== Auth::id()) {
            abort(403);
        }

        if ($friendRequest->status !== 'pending') {
            return response()->json(['message' => 'This request is not pending.'], 400);
        }

        $requesterId = $friendRequest->requester_id;
        $recipientId = $friendRequest->recipient_id;
        $recipient   = Auth::user();
        $requester   = User::find($requesterId);

        if (! $recipient->friends()->where('friend_id', $requesterId)->exists()) {
            $recipient->friends()->attach($requesterId);
        }
        if (! $requester->friends()->where('friend_id', $recipientId)->exists()) {
            $requester->friends()->attach($recipientId);
        }

        $friendRequest->status = 'accepted';
        $friendRequest->save();

        return response()->json(['message' => 'Friend request accepted.']);
    }

    /**
     * Decline a pending friend request.
     * Called from the dashboard decline button via POST /friends/decline
     */
    public function declineRequest(Request $request)
    {
        $request->validate(['request_id' => 'required|exists:friend_requests,id']);

        $friendRequest = FriendRequest::findOrFail($request->input('request_id'));

        if ($friendRequest->recipient_id !== Auth::id()) {
            abort(403);
        }

        $friendRequest->status = 'declined';
        $friendRequest->save();

        return response()->json(['message' => 'Friend request declined.']);
    }
}