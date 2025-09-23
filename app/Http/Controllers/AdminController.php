<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wager;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->get();
        $wager = Wager::with('creator')->orderBy('id')->get();
        return view('Admin.admin', compact('users', 'wager'));
    }

    public function statistics()
    {
        $users = User::orderBy('id')->get();
        $wager = Wager::with('creator')->orderBy('id')->get();
        return view('Admin.statistics', compact('users', 'wager'));
    }

    //funkcijas ar user
    public function deleteUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin')->with('success', 'User deleted successfully.');
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('Admin.Manage.editUser', compact('user'));
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

    // derību funkcijas
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
        $playersDisplay = '';
        $players        = [];

        if (! empty($wager->players) && is_array($wager->players)) {
            $playerNames   = [];
            $uniquePlayers = [];

            foreach ($wager->players as $player) {
                $playerName = null;

                if (is_array($player) && empty(array_filter($player))) {
                    continue;
                }

                if (is_array($player)) {
                    if (! empty($player['name'])) {
                        $playerName = trim($player['name']);
                        $playerData = ['name' => $playerName];
                        if (! empty($player['id'])) {
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

                if (empty($playerName) || in_array(strtolower($playerName), array_map('strtolower', $playerNames))) {
                    continue;
                }

                $playerNames[] = $playerName;
                $players[]     = $playerData;
            }

            $playersDisplay = implode(', ', $playerNames);
        }

        $wager->players = $players;

        return view('Admin.Manage.editWager', [
            'wager'          => $wager,
            'playersDisplay' => $playersDisplay,
        ]);
    }

    public function updateWager(Request $request, $id)
    {
        $wager = Wager::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_players' => 'required|integer|min:2|max:100',
            'status'      => 'required|in:public,private',
            'ending_time' => 'required|date|after:starting_time',
        ]);

        $wager->update($validated);

        return redirect()->route('admin')
            ->with('success', 'Wager updated successfully.');
    }
}
