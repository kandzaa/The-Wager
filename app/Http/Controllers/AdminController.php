<?php
namespace App\Http\Controllers;

use App\Models\Cosmetic;
use App\Models\User;
use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Atgriež administratora galveno pārskata lapu ar statistiku
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'new_users_week'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_wagers'     => Wager::count(),
            'active_wagers'    => Wager::where('status', 'active')->count(),
            'ended_wagers'     => Wager::where('status', 'ended')->count(),
            'total_pot'        => Wager::sum('pot'),
            'total_cosmetics'  => Cosmetic::count(),
            'coins_in_circ'    => User::sum('balance'),
        ];

        $recentUsers  = User::orderByDesc('created_at')->limit(5)->get();
        $recentWagers = Wager::with('creator')->orderByDesc('created_at')->limit(5)->get();

        return view('Admin.admin', compact('stats', 'recentUsers', 'recentWagers'));
    }

    // Atgriež visu lietotāju sarakstu
    public function users()
    {
        $users = User::orderBy('id')->get();
        return view('Admin.users', compact('users'));
    }

    // Atgriež visu derību sarakstu ar to veidotājiem
    public function wagers()
    {
        $wager = Wager::with('creator')->orderBy('id')->get();
        return view('Admin.wagers', compact('wager'));
    }

    // Atgriež lietotāja rediģēšanas veidlapu
    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('Admin.Manage.editUser', compact('user'));
    }

    // Saglabā izmaiņas lietotāja datos
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'balance' => 'required|integer|min:0|max:2147483647',
            'role'    => 'required|in:user,admin,moderator',
        ]);

        if ((int) $id === auth()->id()) {
            unset($validated['role']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin')
            ->with('success', 'User updated successfully.');
    }

    // Izdzēš lietotāju no sistēmas
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin')->with('success', 'User deleted successfully.');
    }

    // Atgriež atsevišķa lietotāja detalizēto skatu
    public function showUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('Admin.Manage.showUser', compact('user'));
    }

    // Izdzēš derību no sistēmas
    public function deleteWager(Request $request, $id)
    {
        $wager = Wager::findOrFail($id);
        $wager->delete();
        return redirect()->route('admin')->with('success', 'Wager deleted successfully.');
    }

    // Atgriež derības rediģēšanas veidlapu
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

    // Saglabā izmaiņas derības datos
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

    // ── Moderation ───────────────────────────────────────────────────────────

    // Aizliedz lietotāja piekļuvi uz norādīto dienu skaitu
    public function banUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ((int) $id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $request->validate([
            'duration' => 'required|integer|min:1|max:365',
            'reason'   => 'nullable|string|max:500',
        ]);

        $user->update([
            'banned_until' => now()->addDays((int) $request->duration),
            'ban_reason'   => $request->reason,
        ]);

        return back()->with('success', "{$user->name} banned for {$request->duration} day(s).");
    }

    // Noņem lietotāja aizliegumu
    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['banned_until' => null, 'ban_reason' => null]);
        return back()->with('success', "{$user->name} has been unbanned.");
    }

    // Iestata lietotāja monētu atlikumu uz nulli
    public function zeroBalance($id)
    {
        $user = User::findOrFail($id);
        $user->update(['balance' => 0]);
        return back()->with('success', "{$user->name}'s balance has been zeroed.");
    }

    // Piespiedu kārtā noslēdz derību
    public function forceEndWager($id)
    {
        $wager = Wager::findOrFail($id);

        if ($wager->status === 'ended') {
            return back()->with('error', 'Wager is already ended.');
        }

        DB::table('wagers')->where('id', $wager->id)->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);

        return back()->with('success', "Wager \"{$wager->name}\" force-ended.");
    }

    //Customizations

    // Atgriež kosmētikas priekšmetu pārvaldības lapu
    public function customizations()
    {
        $cosmetics = Cosmetic::orderByRaw("CASE type WHEN 'frame' THEN 1 WHEN 'title' THEN 2 WHEN 'theme' THEN 3 WHEN 'charm' THEN 4 ELSE 5 END")
            ->orderBy('rarity')
            ->orderBy('name')
            ->get();

        return view('Admin.customizations', compact('cosmetics'));
    }

    // Izveido jaunu kosmētikas priekšmetu
    public function storeCosmetic(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:frame,title,theme,charm',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'price'  => 'required|integer|min:0',
        ]);

        Cosmetic::create([
            'name'   => $request->name,
            'type'   => $request->type,
            'rarity' => $request->rarity,
            'price'  => $request->price,
            'meta'   => json_encode($this->buildMeta($request)),
        ]);

        return redirect()->route('admin.Manage.customizations')->with('success', '"' . $request->name . '" created.');
    }

    // Atjaunina esošā kosmētikas priekšmeta datus
    public function updateCosmetic(Request $request, $id)
    {
        $cosmetic = Cosmetic::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:frame,title,theme,charm',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'price'  => 'required|integer|min:0',
        ]);

        $cosmetic->update([
            'name'   => $request->name,
            'type'   => $request->type,
            'rarity' => $request->rarity,
            'price'  => $request->price,
            'meta'   => json_encode($this->buildMeta($request)),
        ]);

        return redirect()->route('admin.Manage.customizations')->with('success', '"' . $request->name . '" updated.');
    }

    // Izdzēš kosmētikas priekšmetu
    public function destroyCosmetic($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        $name = $cosmetic->name;
        $cosmetic->delete();

        return redirect()->route('admin.Manage.customizations')->with('success', '"' . $name . '" deleted.');
    }

    // Sagatavo kosmētikas metadatus atkarībā no veida
    private function buildMeta(Request $request): array
    {
        return match ($request->type) {
            'frame' => ['gradient' => $request->input('meta_gradient', '')],
            'title' => ['color' => $request->input('meta_color', ''), 'bg' => $request->input('meta_bg', ''), 'hex_color' => $request->input('meta_hex_color', ''), 'hex_bg' => $request->input('meta_hex_bg', '')],
            'theme' => ['gradient' => $request->input('meta_gradient', ''), 'bg_class' => $request->input('meta_bg_class', '')],
            'charm' => ['emoji' => $request->input('meta_emoji', '⭐')],
            default => [],
        };
    }
}
