<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cabinet+Grotesk:wght@400;500;700;800;900&family=DM+Mono:wght@400;500&display=swap');

:root {
    --accent: #10b981;
    --accent-dim: rgba(16,185,129,0.15);
    --border: rgba(255,255,255,0.07);
    --surface: rgba(255,255,255,0.03);
}

.profile-page { font-family: 'Cabinet Grotesk', sans-serif; }
.display { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.05em; }
.mono { font-family: 'DM Mono', monospace; }

/* Tabs */
.tab-btn { transition: all 0.2s; position: relative; }
.tab-btn.active { color: white; }
.tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--accent); border-radius: 99px; }
.tab-content { display: none; }
.tab-content.active { display: block; }

/* Avatar frame styles */
.frame-none .avatar-ring { display: none; }
.frame-gold .avatar-ring { background: linear-gradient(135deg, #f59e0b, #d97706, #fbbf24, #92400e); }
.frame-emerald .avatar-ring { background: linear-gradient(135deg, #10b981, #059669, #34d399); }
.frame-crimson .avatar-ring { background: linear-gradient(135deg, #ef4444, #b91c1c, #f87171); }
.frame-sapphire .avatar-ring { background: linear-gradient(135deg, #3b82f6, #1d4ed8, #60a5fa); }
.frame-void .avatar-ring { background: linear-gradient(135deg, #7c3aed, #4c1d95, #a78bfa); }
.frame-aurora .avatar-ring { background: linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6, #ef4444); }

/* Background themes */
.bg-theme-default { background: #080b0f; }
.bg-theme-midnight { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); }
.bg-theme-forest { background: linear-gradient(135deg, #0a1628, #0d2818, #0a1628); }
.bg-theme-crimson { background: linear-gradient(135deg, #1a0505, #2d0a0a, #1a0505); }
.bg-theme-gold { background: linear-gradient(135deg, #1a1205, #2d2005, #1a1205); }
.bg-theme-void { background: linear-gradient(135deg, #050510, #0d0520, #050510); }

/* Border colors */
.border-theme-default { --profile-border: rgba(255,255,255,0.07); }
.border-theme-gold { --profile-border: rgba(245,158,11,0.4); }
.border-theme-emerald { --profile-border: rgba(16,185,129,0.4); }
.border-theme-crimson { --profile-border: rgba(239,68,68,0.4); }
.border-theme-sapphire { --profile-border: rgba(59,130,246,0.4); }
.border-theme-void { --profile-border: rgba(124,58,237,0.4); }

/* Cosmetic cards */
.cosmetic-card { transition: all 0.25s cubic-bezier(0.4,0,0.2,1); cursor: pointer; }
.cosmetic-card:hover { transform: translateY(-3px); }
.cosmetic-card.owned { border-color: rgba(16,185,129,0.4) !important; }
.cosmetic-card.equipped { border-color: rgba(16,185,129,0.7) !important; background: rgba(16,185,129,0.08) !important; }

/* Stat bars */
.stat-bar { height: 4px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; }
.stat-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #10b981, #34d399); transition: width 1.5s cubic-bezier(0.4,0,0.2,1); }

/* Charm grid */
.charm { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; border: 1px solid var(--border); background: var(--surface); transition: all 0.2s; cursor: pointer; }
.charm:hover { transform: scale(1.1); border-color: rgba(16,185,129,0.4); }
.charm.equipped-charm { border-color: rgba(16,185,129,0.6); background: rgba(16,185,129,0.1); box-shadow: 0 0 12px rgba(16,185,129,0.2); }

/* Animations */
.fade-up { opacity: 0; transform: translateY(20px); animation: fadeUp 0.5s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
@keyframes pulseGlow { 0%,100% { box-shadow: 0 0 20px rgba(16,185,129,0.1); } 50% { box-shadow: 0 0 40px rgba(16,185,129,0.25); } }

.shine { position: relative; overflow: hidden; }
.shine::after { content: ''; position: absolute; top: -50%; left: -60%; width: 40%; height: 200%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent); transform: skewX(-15deg); animation: shine 4s ease-in-out infinite; }
@keyframes shine { 0%,100% { left: -60%; } 50% { left: 120%; } }

.title-tag { display: inline-block; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; }
</style>

<div class="profile-page min-h-screen bg-theme-default text-white" id="profilePage">

    {{-- Dynamic background blobs based on theme --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" id="bgBlobs">
        <div class="absolute top-0 left-1/3 w-[700px] h-[600px] bg-emerald-900/10 rounded-full blur-[150px]" id="blob1"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-emerald-950/15 rounded-full blur-[120px]" id="blob2"></div>
        <div class="absolute top-1/2 left-0 w-[300px] h-[300px] bg-slate-800/20 rounded-full blur-[80px]"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12">

        {{-- PROFILE HERO --}}
        <div class="fade-up mb-8" style="animation-delay:0ms">
            <div class="rounded-3xl border p-8 relative overflow-hidden shine pulse-glow" style="border-color: var(--profile-border, rgba(255,255,255,0.07)); background: rgba(255,255,255,0.02);" id="profileCard">

                {{-- Background pattern --}}
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.5) 0, rgba(255,255,255,0.5) 1px, transparent 0, transparent 50%); background-size: 20px 20px;"></div>

                <div class="relative flex flex-col md:flex-row items-start md:items-center gap-8">

                    {{-- Avatar with frame --}}
                    <div class="relative shrink-0 frame-emerald" id="avatarWrapper">
                        <div class="avatar-ring w-28 h-28 rounded-[22px] p-[3px]">
                            <div class="w-full h-full rounded-[19px] bg-[#0d1117] flex items-center justify-center">
                                <span class="display text-5xl text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        {{-- Equipped charms --}}
                        <div class="absolute -bottom-2 -right-2 flex gap-1" id="equippedCharmsDisplay">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 border border-white/10 flex items-center justify-center text-base">⚡</div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap mb-2">
                            <h1 class="display text-5xl text-white tracking-wide">{{ Auth::user()->name }}</h1>
                            {{-- Equipped title --}}
                            <span class="title-tag bg-emerald-500/10 border border-emerald-500/30 text-emerald-400" id="equippedTitleDisplay">High Roller</span>
                        </div>
                        <p class="mono text-xs text-slate-500 mb-4">Member since {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('M Y') }}</p>

                        {{-- Stats row --}}
                        <div class="flex flex-wrap gap-6">
                            @php
                                $user = Auth::user();
                                $totalWagers = \App\Models\WagerBet::where('wager_player_id', function($q) use ($user) {
                                    $q->select('id')->from('wager_players')->where('user_id', $user->id);
                                })->count();
                                $wonBets = \App\Models\WagerBet::where('wager_player_id', function($q) use ($user) {
                                    $q->select('id')->from('wager_players')->where('user_id', $user->id);
                                })->where('status', 'won')->count();
                                $totalPayout = \App\Models\WagerBet::where('wager_player_id', function($q) use ($user) {
                                    $q->select('id')->from('wager_players')->where('user_id', $user->id);
                                })->sum('payout');
                                $winRate = $totalWagers > 0 ? round(($wonBets / $totalWagers) * 100) : 0;
                            @endphp
                            <div>
                                <p class="mono text-xs text-slate-600 mb-0.5">Balance</p>
                                <p class="display text-2xl text-emerald-400">{{ number_format($user->balance, 0) }}</p>
                            </div>
                            <div>
                                <p class="mono text-xs text-slate-600 mb-0.5">Win Rate</p>
                                <p class="display text-2xl text-white">{{ $winRate }}%</p>
                            </div>
                            <div>
                                <p class="mono text-xs text-slate-600 mb-0.5">Total Bets</p>
                                <p class="display text-2xl text-white">{{ $totalWagers }}</p>
                            </div>
                            <div>
                                <p class="mono text-xs text-slate-600 mb-0.5">Total Won</p>
                                <p class="display text-2xl text-amber-400">{{ number_format($totalPayout, 0) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Balance badge --}}
                    <div class="shrink-0 text-right">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/20">
                            <span class="text-amber-400 text-lg">🪙</span>
                            <span class="display text-2xl text-amber-400">{{ number_format($user->balance, 0) }}</span>
                        </div>
                        <p class="mono text-xs text-slate-600 mt-1">available coins</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="fade-up mb-6" style="animation-delay:80ms">
            <div class="flex items-center gap-0 border-b border-white/[0.07]">
                @foreach(['stats' => 'Stats & History', 'shop' => '🛍 Shop', 'customize' => '🎨 Customize', 'settings' => 'Settings'] as $tab => $label)
                <button onclick="switchTab('{{ $tab }}')" class="tab-btn px-6 py-3 text-sm font-bold text-slate-500 hover:text-white {{ $tab === 'stats' ? 'active' : '' }}" id="tab-{{ $tab }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- TAB: STATS --}}
        <div class="tab-content active fade-up" id="content-stats" style="animation-delay:120ms">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left: detailed stats --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Win/Loss breakdown --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                        <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500 mb-5">Performance</p>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @foreach([
                                ['label' => 'Won', 'value' => $wonBets, 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-500/10'],
                                ['label' => 'Lost', 'value' => $totalWagers - $wonBets, 'color' => 'text-red-400', 'bg' => 'bg-red-500/10'],
                                ['label' => 'Total', 'value' => $totalWagers, 'color' => 'text-white', 'bg' => 'bg-white/5'],
                            ] as $s)
                            <div class="rounded-xl {{ $s['bg'] }} p-4 text-center">
                                <p class="display text-4xl {{ $s['color'] }}">{{ $s['value'] }}</p>
                                <p class="mono text-xs text-slate-500 mt-1">{{ $s['label'] }}</p>
                            </div>
                            @endforeach
                        </div>
                        {{-- Win rate bar --}}
                        <div class="mb-2 flex justify-between items-center">
                            <span class="mono text-xs text-slate-500">Win Rate</span>
                            <span class="mono text-xs text-emerald-400">{{ $winRate }}%</span>
                        </div>
                        <div class="stat-bar">
                            <div class="stat-bar-fill" style="width: {{ $winRate }}%"></div>
                        </div>
                    </div>

                    {{-- Recent wagers --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                        <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500 mb-4">Recent Wagers</p>
                        @php
                            $recentBets = \Illuminate\Support\Facades\DB::table('wager_bets as b')
                                ->join('wager_players as p', 'b.wager_player_id', '=', 'p.id')
                                ->join('wagers as w', 'b.wager_id', '=', 'w.id')
                                ->where('p.user_id', Auth::id())
                                ->select('w.name', 'b.bet_amount', 'b.payout', 'b.status', 'b.updated_at')
                                ->orderByDesc('b.updated_at')
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($recentBets as $bet)
                        <div class="flex items-center justify-between py-3 border-b border-white/[0.04] last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $bet->status === 'won' ? 'bg-emerald-500' : ($bet->status === 'lost' ? 'bg-red-500' : 'bg-slate-600') }}"></div>
                                <span class="text-sm font-semibold text-slate-300 truncate max-w-[200px]">{{ $bet->name }}</span>
                            </div>
                            <div class="flex items-center gap-4 mono text-xs">
                                <span class="text-slate-500">{{ number_format($bet->bet_amount, 0) }}</span>
                                <span class="{{ $bet->status === 'won' ? 'text-emerald-400' : 'text-red-400' }} font-bold">
                                    {{ $bet->status === 'won' ? '+'.number_format($bet->payout - $bet->bet_amount, 0) : '-'.number_format($bet->bet_amount, 0) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-slate-600 text-sm text-center py-4">No bets yet</p>
                        @endforelse
                        <a href="{{ route('history') }}" class="mt-4 block text-center mono text-xs text-emerald-500 hover:text-emerald-400 transition-colors">View full history →</a>
                    </div>
                </div>

                {{-- Right: equipped cosmetics showcase --}}
                <div class="space-y-4">
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                        <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500 mb-4">Equipped</p>
                        <div class="space-y-3">
                            @foreach([
                                ['label' => 'Frame', 'value' => 'Emerald', 'icon' => '🟢'],
                                ['label' => 'Title', 'value' => 'High Roller', 'icon' => '👑'],
                                ['label' => 'Theme', 'value' => 'Default', 'icon' => '🎨'],
                                ['label' => 'Charm 1', 'value' => '⚡ Lightning', 'icon' => '⚡'],
                            ] as $item)
                            <div class="flex items-center justify-between py-2.5 px-3 rounded-xl bg-white/[0.03] border border-white/[0.05]">
                                <span class="mono text-xs text-slate-500">{{ $item['label'] }}</span>
                                <span class="text-xs font-bold text-slate-300">{{ $item['value'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Earnings card --}}
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-6">
                        <p class="mono text-xs uppercase tracking-[0.2em] text-amber-500/70 mb-3">Total Earnings</p>
                        <p class="display text-5xl text-amber-400">{{ number_format($totalPayout, 0) }}</p>
                        <p class="mono text-xs text-slate-600 mt-1">coins won all time</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: SHOP --}}
        <div class="tab-content" id="content-shop">
            @php
                $shopItems = [
                    'frames' => [
                        ['id' => 'frame_gold', 'name' => 'Gold Frame', 'desc' => 'Pure prestige', 'price' => 500, 'preview' => 'linear-gradient(135deg, #f59e0b, #d97706, #fbbf24)', 'rarity' => 'rare'],
                        ['id' => 'frame_crimson', 'name' => 'Crimson Frame', 'desc' => 'For the bold', 'price' => 400, 'preview' => 'linear-gradient(135deg, #ef4444, #b91c1c)', 'rarity' => 'uncommon'],
                        ['id' => 'frame_sapphire', 'name' => 'Sapphire Frame', 'desc' => 'Ice cold', 'price' => 450, 'preview' => 'linear-gradient(135deg, #3b82f6, #1d4ed8)', 'rarity' => 'uncommon'],
                        ['id' => 'frame_void', 'name' => 'Void Frame', 'desc' => 'From the dark', 'price' => 800, 'preview' => 'linear-gradient(135deg, #7c3aed, #4c1d95)', 'rarity' => 'epic'],
                        ['id' => 'frame_aurora', 'name' => 'Aurora Frame', 'desc' => 'All the colors', 'price' => 1200, 'preview' => 'linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6, #ef4444)', 'rarity' => 'legendary'],
                    ],
                    'titles' => [
                        ['id' => 'title_whale', 'name' => 'The Whale', 'desc' => 'Big money', 'price' => 600, 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10 border-blue-500/30', 'rarity' => 'rare'],
                        ['id' => 'title_shark', 'name' => 'Card Shark', 'desc' => 'Always wins', 'price' => 800, 'color' => 'text-slate-300', 'bg' => 'bg-slate-500/10 border-slate-500/30', 'rarity' => 'rare'],
                        ['id' => 'title_legend', 'name' => 'Legend', 'desc' => 'Self-explanatory', 'price' => 2000, 'color' => 'text-amber-400', 'bg' => 'bg-amber-500/10 border-amber-500/30', 'rarity' => 'legendary'],
                        ['id' => 'title_ghost', 'name' => 'Ghost Bettor', 'desc' => 'Silent hunter', 'price' => 350, 'color' => 'text-slate-400', 'bg' => 'bg-slate-500/10 border-slate-500/30', 'rarity' => 'common'],
                    ],
                    'themes' => [
                        ['id' => 'theme_midnight', 'name' => 'Midnight', 'desc' => 'Deep purple night', 'price' => 300, 'preview' => 'linear-gradient(135deg, #0f0c29, #302b63)', 'rarity' => 'common'],
                        ['id' => 'theme_forest', 'name' => 'Forest', 'desc' => 'Dark and deep', 'price' => 300, 'preview' => 'linear-gradient(135deg, #0a1628, #0d2818)', 'rarity' => 'common'],
                        ['id' => 'theme_crimson', 'name' => 'Crimson', 'desc' => 'Blood money', 'price' => 500, 'preview' => 'linear-gradient(135deg, #1a0505, #2d0a0a)', 'rarity' => 'uncommon'],
                        ['id' => 'theme_void', 'name' => 'The Void', 'desc' => 'Pure darkness', 'price' => 700, 'preview' => 'linear-gradient(135deg, #050510, #0d0520)', 'rarity' => 'epic'],
                    ],
                    'charms' => [
                        ['id' => 'charm_fire', 'name' => 'Fire', 'emoji' => '🔥', 'price' => 150, 'rarity' => 'common'],
                        ['id' => 'charm_skull', 'name' => 'Skull', 'emoji' => '💀', 'price' => 200, 'rarity' => 'uncommon'],
                        ['id' => 'charm_crown', 'name' => 'Crown', 'emoji' => '👑', 'price' => 400, 'rarity' => 'rare'],
                        ['id' => 'charm_gem', 'name' => 'Gem', 'emoji' => '💎', 'price' => 300, 'rarity' => 'rare'],
                        ['id' => 'charm_rocket', 'name' => 'Rocket', 'emoji' => '🚀', 'price' => 250, 'rarity' => 'uncommon'],
                        ['id' => 'charm_snake', 'name' => 'Snake', 'emoji' => '🐍', 'price' => 175, 'rarity' => 'common'],
                        ['id' => 'charm_star', 'name' => 'Star', 'emoji' => '⭐', 'price' => 200, 'rarity' => 'common'],
                        ['id' => 'charm_wolf', 'name' => 'Wolf', 'emoji' => '🐺', 'price' => 350, 'rarity' => 'rare'],
                        ['id' => 'charm_lightning', 'name' => 'Lightning', 'emoji' => '⚡', 'price' => 150, 'rarity' => 'common'],
                        ['id' => 'charm_dagger', 'name' => 'Dagger', 'emoji' => '🗡️', 'price' => 300, 'rarity' => 'uncommon'],
                        ['id' => 'charm_eye', 'name' => 'Eye', 'emoji' => '👁️', 'price' => 500, 'rarity' => 'epic'],
                        ['id' => 'charm_moon', 'name' => 'Moon', 'emoji' => '🌙', 'price' => 225, 'rarity' => 'common'],
                    ],
                ];
                $rarityColors = ['common' => 'text-slate-400 border-slate-600/50', 'uncommon' => 'text-green-400 border-green-600/50', 'rare' => 'text-blue-400 border-blue-600/50', 'epic' => 'text-purple-400 border-purple-600/50', 'legendary' => 'text-amber-400 border-amber-600/50'];
            @endphp

            {{-- Shop category tabs --}}
            <div class="flex gap-2 mb-6 flex-wrap">
                @foreach(['frames' => '🖼 Frames', 'titles' => '👑 Titles', 'themes' => '🎨 Themes', 'charms' => '✨ Charms'] as $cat => $label)
                <button onclick="switchShopCat('{{ $cat }}')" id="shopcat-{{ $cat }}" class="px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-200 {{ $cat === 'frames' ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-white/[0.03] border-white/[0.07] text-slate-500 hover:text-white' }}">{{ $label }}</button>
                @endforeach
            </div>

            {{-- Frames --}}
            <div id="shopcat-content-frames">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($shopItems['frames'] as $item)
                    <div class="cosmetic-card rounded-2xl border border-white/[0.07] bg-white/[0.02] p-4 text-center">
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-3 p-[3px]" style="background: {{ $item['preview'] }}">
                            <div class="w-full h-full rounded-[14px] bg-[#0d1117] flex items-center justify-center">
                                <span class="display text-2xl text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <p class="font-bold text-sm text-white mb-0.5">{{ $item['name'] }}</p>
                        <p class="mono text-xs {{ explode(' ', $rarityColors[$item['rarity']])[0] }} mb-3">{{ ucfirst($item['rarity']) }}</p>
                        <button class="w-full py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 transition-all">
                            🪙 {{ number_format($item['price']) }}
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Titles --}}
            <div id="shopcat-content-titles" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($shopItems['titles'] as $item)
                    <div class="cosmetic-card rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5 flex items-center justify-between">
                        <div>
                            <span class="title-tag {{ $item['bg'] }} {{ $item['color'] }} mb-2 inline-block">{{ $item['name'] }}</span>
                            <p class="text-xs text-slate-500">{{ $item['desc'] }}</p>
                            <p class="mono text-xs {{ explode(' ', $rarityColors[$item['rarity']])[0] }} mt-1">{{ ucfirst($item['rarity']) }}</p>
                        </div>
                        <button class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 transition-all ml-4">
                            🪙 {{ number_format($item['price']) }}
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Themes --}}
            <div id="shopcat-content-themes" class="hidden">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($shopItems['themes'] as $item)
                    <div class="cosmetic-card rounded-2xl border border-white/[0.07] overflow-hidden">
                        <div class="h-24 w-full" style="background: {{ $item['preview'] }}"></div>
                        <div class="p-4">
                            <p class="font-bold text-sm text-white mb-0.5">{{ $item['name'] }}</p>
                            <p class="text-xs text-slate-500 mb-3">{{ $item['desc'] }}</p>
                            <button class="w-full py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 transition-all">
                                🪙 {{ number_format($item['price']) }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Charms --}}
            <div id="shopcat-content-charms" class="hidden">
                <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach($shopItems['charms'] as $item)
                    <div class="cosmetic-card rounded-2xl border border-white/[0.07] bg-white/[0.02] p-4 text-center">
                        <div class="text-4xl mb-2">{{ $item['emoji'] }}</div>
                        <p class="font-bold text-xs text-white mb-0.5">{{ $item['name'] }}</p>
                        <p class="mono text-[10px] {{ explode(' ', $rarityColors[$item['rarity']])[0] }} mb-2">{{ ucfirst($item['rarity']) }}</p>
                        <button class="w-full py-1.5 rounded-lg text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 transition-all">
                            🪙 {{ $item['price'] }}
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TAB: CUSTOMIZE --}}
        <div class="tab-content" id="content-customize">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Live preview --}}
                <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                    <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500 mb-4">Live Preview</p>
                    <div class="rounded-2xl border p-6 text-center" style="border-color: var(--profile-border, rgba(255,255,255,0.1));" id="previewCard">
                        <div class="flex flex-col items-center gap-3">
                            <div class="relative" id="previewAvatarWrapper">
                                <div class="avatar-ring w-20 h-20 rounded-[18px] p-[3px] frame-emerald" id="previewFrame">
                                    <div class="w-full h-full rounded-[15px] bg-[#0d1117] flex items-center justify-center">
                                        <span class="display text-3xl text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="display text-2xl text-white">{{ Auth::user()->name }}</p>
                                <span class="title-tag bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mt-1 inline-block" id="previewTitle">High Roller</span>
                            </div>
                            <div class="flex gap-2" id="previewCharms">
                                <span class="charm equipped-charm">⚡</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customize options --}}
                <div class="space-y-4">
                    {{-- Frame picker --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mono text-xs uppercase tracking-[0.15em] text-slate-500 mb-3">Avatar Frame</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['none' => '#374151', 'emerald' => 'linear-gradient(135deg,#10b981,#059669)', 'gold' => 'linear-gradient(135deg,#f59e0b,#d97706)', 'crimson' => 'linear-gradient(135deg,#ef4444,#b91c1c)', 'sapphire' => 'linear-gradient(135deg,#3b82f6,#1d4ed8)', 'void' => 'linear-gradient(135deg,#7c3aed,#4c1d95)', 'aurora' => 'linear-gradient(135deg,#10b981,#3b82f6,#8b5cf6,#ef4444)'] as $fname => $fcolor)
                            <button onclick="setFrame('{{ $fname }}')" class="w-10 h-10 rounded-xl border-2 border-transparent hover:border-white/30 transition-all" style="background: {{ $fcolor }}; {{ $fname === 'none' ? 'border-color: rgba(255,255,255,0.1)' : '' }}" title="{{ ucfirst($fname) }}"></button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Charm slots --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mono text-xs uppercase tracking-[0.15em] text-slate-500 mb-3">Charm Slots</p>
                        <div class="grid grid-cols-6 gap-2 mb-3">
                            @foreach(['⚡','🔥','💀','👑','💎','🚀','🐍','⭐','🐺','🗡️','👁️','🌙'] as $charm)
                            <button onclick="toggleCharm('{{ $charm }}')" class="charm" data-charm="{{ $charm }}">{{ $charm }}</button>
                            @endforeach
                        </div>
                        <p class="mono text-xs text-slate-600">Click to equip (max 3)</p>
                    </div>

                    {{-- Save button --}}
                    <button class="w-full py-3 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white transition-all active:scale-95">
                        Save Customization
                    </button>
                </div>
            </div>
        </div>

        {{-- TAB: SETTINGS --}}
        <div class="tab-content" id="content-settings">
            <div class="max-w-xl space-y-4">

                @if(session('success'))
                <div class="px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.05]">
                        <p class="mono text-xs uppercase tracking-[0.15em] text-slate-500">Username</p>
                    </div>
                    <div class="px-6 py-5">
                        <form action="{{ route('profile.change-username') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all active:scale-95">Save</button>
                        </form>
                        @error('name')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.05]">
                        <p class="mono text-xs uppercase tracking-[0.15em] text-slate-500">Email Address</p>
                    </div>
                    <div class="px-6 py-5">
                        <form action="{{ route('profile.change-email') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all active:scale-95">Save</button>
                        </form>
                        @error('email')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="rounded-2xl border border-red-500/20 bg-red-950/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-500/10">
                        <p class="mono text-xs uppercase tracking-[0.15em] text-red-500/70">Danger Zone</p>
                    </div>
                    <div class="px-6 py-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-300">Delete Account</p>
                            <p class="text-xs text-slate-600 mt-0.5">Permanently remove everything.</p>
                        </div>
                        <button class="px-4 py-2 bg-red-950/60 hover:bg-red-900/60 border border-red-500/20 hover:border-red-500/40 text-red-400 text-sm font-bold rounded-xl transition-all active:scale-95">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Tabs
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('content-' + tab).classList.add('active');
}

// Shop categories
function switchShopCat(cat) {
    ['frames','titles','themes','charms'].forEach(c => {
        const btn = document.getElementById('shopcat-' + c);
        const content = document.getElementById('shopcat-content-' + c);
        if (c === cat) {
            btn.className = btn.className.replace('bg-white/[0.03] border-white/[0.07] text-slate-500 hover:text-white', 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400');
            content.classList.remove('hidden');
        } else {
            btn.className = btn.className.replace('bg-emerald-500/10 border-emerald-500/30 text-emerald-400', 'bg-white/[0.03] border-white/[0.07] text-slate-500 hover:text-white');
            content.classList.add('hidden');
        }
    });
}

// Frame setter
function setFrame(frame) {
    const frameClasses = ['frame-none','frame-gold','frame-emerald','frame-crimson','frame-sapphire','frame-void','frame-aurora'];
    const wrapper = document.getElementById('avatarWrapper');
    const preview = document.getElementById('previewFrame');
    frameClasses.forEach(c => { wrapper.classList.remove(c); preview.classList.remove(c); });
    wrapper.classList.add('frame-' + frame);
    preview.classList.add('frame-' + frame);
}

// Charms
let equippedCharms = ['⚡'];
function toggleCharm(charm) {
    const btn = document.querySelector(`[data-charm="${charm}"]`);
    if (equippedCharms.includes(charm)) {
        equippedCharms = equippedCharms.filter(c => c !== charm);
        btn.classList.remove('equipped-charm');
    } else {
        if (equippedCharms.length >= 3) { alert('Max 3 charms!'); return; }
        equippedCharms.push(charm);
        btn.classList.add('equipped-charm');
    }
    updateCharmDisplay();
}
function updateCharmDisplay() {
    const display = document.getElementById('previewCharms');
    display.innerHTML = equippedCharms.map(c => `<span class="charm equipped-charm">${c}</span>`).join('');
    const mainDisplay = document.getElementById('equippedCharmsDisplay');
    mainDisplay.innerHTML = equippedCharms.map(c => `<div class="w-8 h-8 rounded-lg bg-slate-900 border border-white/10 flex items-center justify-center text-base">${c}</div>`).join('');
}

// Animate stat bars on load
window.addEventListener('load', () => {
    document.querySelectorAll('.stat-bar-fill').forEach(bar => {
        const w = bar.style.width; bar.style.width = '0';
        setTimeout(() => bar.style.width = w, 400);
    });
    // Set initial charm state
    document.querySelector('[data-charm="⚡"]')?.classList.add('equipped-charm');
});
</script>
</x-app-layout>