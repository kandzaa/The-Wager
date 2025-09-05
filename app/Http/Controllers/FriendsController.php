<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index()
    {
        $friends = Auth::user()->friends;
        return view('Friends.friends', compact('friends'));
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query', '');

        if (empty($query)) {
            return response()->json([]);
        }

        // Fixed: Added 'id' to the get() method
        $users = User::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name', 'email', 'created_at']); // Added 'id' here

        return response()->json($users->map(function ($user) {
            return [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'joined'  => $user->created_at->diffForHumans(),
                'initial' => substr($user->name, 0, 1),
            ];
        }));
    }

    public function addFriend(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $friendId = $request->input('friend_id');
        $user     = Auth::user();

        if ($user->id == $friendId) {
            return response()->json(['message' => 'You cannot add yourself as a friend.'], 400);
        }

        if ($user->friends()->where('friend_id', $friendId)->exists()) {
            return response()->json(['message' => 'This user is already your friend.'], 400);
        }

        $user->friends()->attach($friendId);

        return response()->json(['message' => 'Friend added successfully.']);
    }

    public function showUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->route('friends')->with('error', 'User not found.');
        }

        // Fixed: Match the actual view file location
        return view('Friends.user-show', compact('user'));
    }
}
