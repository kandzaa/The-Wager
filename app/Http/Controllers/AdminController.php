<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Basic Stats
        $stats = [
            'total_wagers'     => Wager::count(),
            'active_wagers'    => Wager::where('status', 'active')->count(),
            'pending_wagers'   => Wager::where('status', 'pending')->count(),
            'completed_wagers' => Wager::where('status', 'ended')->count(),
            'total_users'      => User::count(),
            'total_wagered'    => Wager::sum('pot'),
            'avg_pot'          => Wager::where('pot', '>', 0)->avg('pot') ?? 0,
        ];

        // Metrics
        $metrics = [
            'active_wagers_ending_soon' => Wager::where('status', 'active')
                ->where('ending_time', '<=', now()->addDays(2))
                ->count(),
            'total_wagered_this_week'   => DB::table('wager_bets')
                ->where('created_at', '>=', now()->subWeek())
                ->sum('bet_amount') ?? 0,
            'new_users_this_week'       => User::where('created_at', '>=', now()->subWeek())->count(),
            'recent_payouts'            => DB::table('wager_bets')
                ->where('is_win', true)
                ->where('updated_at', '>=', now()->subWeek())
                ->sum('payout') ?? 0,
        ];

        // Wagers Over Time (last 14 days)
        $wagersOverTime = Wager::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date'  => $item->date,
                    'count' => $item->count,
                ];
            });

        // Fill in missing dates with 0
        $filledData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date         = now()->subDays($i)->format('Y-m-d');
            $found        = $wagersOverTime->firstWhere('date', $date);
            $filledData[] = [
                'date'  => $date,
                'count' => $found ? $found['count'] : 0,
            ];
        }
        $wagersOverTime = $filledData;

        // Wagers by Status
        $wagersByStatus = Wager::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'count'  => $item->count,
                ];
            });

        // Top Wagers by Pot
        $topWagers = Wager::withCount('players')
            ->selectRaw('wagers.*, COALESCE(wagers.pot, 0) as total_amount')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // Recent Activity (bets placed)
        $recentActivity = DB::table('wager_bets')
            ->join('wager_players', 'wager_bets.wager_player_id', '=', 'wager_players.id')
            ->join('users', 'wager_players.user_id', '=', 'users.id')
            ->join('wagers', 'wager_bets.wager_id', '=', 'wagers.id')
            ->select(
                'users.name as user_name',
                'users.avatar as user_avatar',
                'wagers.name as wager_name',
                'wagers.pot as wager_pot',
                'wager_bets.bet_amount as amount',
                'wager_bets.created_at',
                DB::raw("CASE
                WHEN wager_bets.is_win = true THEN 'won'
                WHEN wager_bets.is_win = false THEN 'lost'
                ELSE 'pending'
            END as status")
            )
            ->orderBy('wager_bets.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->created_at = \Carbon\Carbon::parse($item->created_at);
                return $item;
            });

        return view('Admin.Statistics.statistics', compact(
            'stats',
            'metrics',
            'wagersOverTime',
            'wagersByStatus',
            'topWagers',
            'recentActivity'
        ));
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
