<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wager;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->get();
        $wager = Wager::with('creator')->orderBy('id')->get();
        return view('Admin.admin', compact('users', 'wager'));
    }

    public function deleteUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin')->with('success', 'User deleted successfully.');
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('Admin.editUser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'balance' => 'required|numeric',
            'role'    => 'required|in:user,admin,moderator',
        ]);

        $user->update($validated);
        
        return redirect()
            ->route('admin')
            ->with('success', 'User updated successfully.');
    }

    // Wager management
    public function deleteWager(Request $request, $id)
    {
        $wager = Wager::findOrFail($id);
        $wager->delete();
        return redirect()->route('admin')->with('success', 'Wager deleted successfully.');
    }

    public function editWager(Request $request, $id)
    {
        $wager = \App\Models\Wager::findOrFail($id);
        if ($request->isMethod('post')) {
            $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'max_players' => 'required|integer|min:2|max:100',
                'visibility'  => 'required|in:public,private',
                'ending_time' => 'required|date|after:now',
            ]);
            $wager->update($request->only('name', 'description', 'max_players', 'visibility', 'ending_time'));
            return redirect()->route('admin')->with('success', 'Wager updated successfully.');
        }
        // Process players data for display
        $playersDisplay = '';
        $players = [];
        
        if (!empty($wager->players) && is_array($wager->players)) {
            $playerNames = [];
            $uniquePlayers = [];
            
            foreach ($wager->players as $player) {
                $playerName = null;
                
                // Skip empty arrays
                if (is_array($player) && empty(array_filter($player))) {
                    continue;
                }
                
                // Handle different player formats
                if (is_array($player)) {
                    if (!empty($player['name'])) {
                        $playerName = trim($player['name']);
                        $playerData = ['name' => $playerName];
                        if (!empty($player['id'])) {
                            $playerData['id'] = $player['id'];
                        }
                    } elseif (isset($player[0]) && is_string($player[0])) {
                        $playerName = trim($player[0]);
                        $playerData = ['name' => $playerName];
                    }
                } elseif (is_string($player) && trim($player) !== '') {
                    $playerName = trim($player);
                    $playerData = ['name' => $playerName];
                }
                
                // Skip if no valid player name found or duplicate
                if (empty($playerName) || in_array(strtolower($playerName), array_map('strtolower', $playerNames))) {
                    continue;
                }
                
                $playerNames[] = $playerName;
                $players[] = $playerData;
            }
            
            $playersDisplay = implode(', ', $playerNames);
        }
        
        // Update the wager's players with the processed data
        $wager->players = $players;
        
        return view('Admin.editWager', [
            'wager' => $wager,
            'playersDisplay' => $playersDisplay
        ]);
    }

    public function updateWager(Request $request, $id)
    {
        $wager = Wager::findOrFail($id);
        
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'max_players'   => 'required|integer|min:2|max:100',
            'status'        => 'required|in:public,private',
            'starting_time' => 'required|date|after:now',
            'ending_time'   => 'required|date|after:starting_time',
            'pot'           => 'required|numeric|min:0',
            'players'       => 'nullable|string',
        ]);

        // Process players string into array of user data
        $players = [];
        if (!empty($validated['players'])) {
            $playerIdentifiers = array_map('trim', explode(',', $validated['players']));
            foreach ($playerIdentifiers as $identifier) {
                // Try to find user by ID or username
                $user = User::where('id', $identifier)
                    ->orWhere('name', $identifier)
                    ->first();
                
                if ($user) {
                    $players[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'joined_at' => now()->toDateTimeString(),
                    ];
                }
            }
        }

        // Update wager with validated data
        $wager->update([
            'name'          => $validated['name'],
            'description'   => $validated['description'],
            'max_players'   => $validated['max_players'],
            'status'        => $validated['status'],
            'starting_time' => $validated['starting_time'],
            'ending_time'   => $validated['ending_time'],
            'pot'           => $validated['pot'],
            'players'       => $players,
        ]);

        return redirect()->route('admin')
            ->with('success', 'Wager updated successfully.');
    }
}
