<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cabinet+Grotesk:wght@400;700;800;900&family=DM+Mono:wght@400;500&display=swap');

:root { --accent:#10b981; }
.pf  { font-family:'Cabinet Grotesk',sans-serif; }
.dsp { font-family:'Bebas Neue',sans-serif; letter-spacing:0.05em; }
.mn  { font-family:'DM Mono',monospace; }

.fu { opacity:0; transform:translateY(14px); animation:fu .4s ease forwards; }
@keyframes fu { to { opacity:1; transform:translateY(0); } }

.tb { padding:12px 20px; font-size:13px; font-weight:800; transition:color .2s; position:relative; }
.tb:hover { color:#10b981; }
.tb.on { color:#10b981; }
.tb.on::after { content:''; position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#10b981; border-radius:99px; }
.tc { display:none; }
.tc.on { display:block; animation:fu .25s ease; }

/* Light mode tab text */
html:not(.dark) .tb       { color:#64748b; }
html:not(.dark) .tb:hover { color:#10b981; }
html:not(.dark) .tb.on    { color:#10b981; }

/* Dark mode tab text */
html.dark .tb       { color:#475569; }
html.dark .tb:hover { color:#fff; }
html.dark .tb.on    { color:#fff; }

.av-ring { display:block; padding:3px; }

/* Theme backgrounds — dark */
html.dark .bg-profile-default  { background:#080b0f; }
html.dark .bg-profile-midnight { background:linear-gradient(160deg,#0f0c29 0%,#1a1640 100%); }
html.dark .bg-profile-crimson  { background:linear-gradient(160deg,#1a0505 0%,#2d0a0a 100%); }
html.dark .bg-profile-void     { background:linear-gradient(160deg,#050510 0%,#0d0520 100%); }

/* Theme backgrounds — light */
html:not(.dark) .bg-profile-default  { background:#f8fafc; }
html:not(.dark) .bg-profile-midnight { background:linear-gradient(160deg,#ede9fe 0%,#e0e7ff 100%); }
html:not(.dark) .bg-profile-crimson  { background:linear-gradient(160deg,#fff1f2 0%,#ffe4e6 100%); }
html:not(.dark) .bg-profile-void     { background:linear-gradient(160deg,#f5f3ff 0%,#ede9fe 100%); }

.r-common    { color:#94a3b8; }
.r-uncommon  { color:#16a34a; }
.r-rare      { color:#2563eb; }
.r-epic      { color:#7c3aed; }
.r-legendary { color:#d97706; }

html.dark .r-uncommon  { color:#4ade80; }
html.dark .r-rare      { color:#60a5fa; }
html.dark .r-epic      { color:#a78bfa; }
html.dark .r-legendary { color:#fbbf24; }

.ttag { display:inline-block; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; border:1px solid; }

.ccard { transition:transform .2s,box-shadow .2s; }
.ccard:hover { transform:translateY(-2px); }
.ccard.is-equipped { box-shadow:0 0 0 2px #10b981,0 0 18px rgba(16,185,129,.2); }

.sbar { height:4px; border-radius:99px; overflow:hidden; }
html.dark  .sbar { background:rgba(255,255,255,.05); }
html:not(.dark) .sbar { background:rgba(0,0,0,.07); }
.sfil { height:100%; background:linear-gradient(90deg,#10b981,#34d399); border-radius:99px; transition:width 1.4s cubic-bezier(.4,0,.2,1); }

.bpulse { animation:bp 2.5s ease-in-out infinite; }
@keyframes bp { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.2)} 50%{box-shadow:0 0 0 8px rgba(16,185,129,0)} }

.toast { position:fixed; bottom:24px; right:24px; z-index:9999; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; font-family:'DM Mono',monospace; transform:translateY(60px); opacity:0; transition:all .3s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
.toast.show { transform:translateY(0); opacity:1; }
.toast.ok  { background:#0d2818; border:1px solid rgba(16,185,129,.4); color:#34d399; }
.toast.err { background:#2d0a0a; border:1px solid rgba(239,68,68,.4);  color:#f87171; }
html:not(.dark) .toast.ok  { background:#ecfdf5; border-color:#6ee7b7; color:#065f46; }
html:not(.dark) .toast.err { background:#fff1f2; border-color:#fca5a5; color:#991b1b; }

.eq-btn { width:100%; padding:8px; border-radius:10px; font-size:12px; font-weight:700; transition:all .2s; border:1px solid; }
.eq-off { background:rgba(16,185,129,.07); border-color:rgba(16,185,129,.2); color:#10b981; }
.eq-off:hover { background:rgba(16,185,129,.15); }
.eq-on  { background:rgba(16,185,129,.18); border-color:rgba(16,185,129,.5); color:#059669; }
html.dark .eq-on { color:#34d399; }

/* Card backgrounds */
.prof-card {
    border-radius:1rem;
    border-width:1px;
    border-style:solid;
}
html.dark  .prof-card { background:rgba(255,255,255,.02); border-color:rgba(255,255,255,.07); }
html:not(.dark) .prof-card { background:#ffffff; border-color:#e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,.06); }

html.dark  .prof-hero  { background:rgba(255,255,255,.02); border-color:rgba(255,255,255,.08); }
html:not(.dark) .prof-hero { background:#ffffff; border-color:#e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,.06); }

html.dark  .prof-inner { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.05); }
html:not(.dark) .prof-inner { background:#f8fafc; border-color:#e2e8f0; }

/* Frosted-glass cards when a custom gradient theme is active */
#profile-root.has-theme .prof-card {
    background: rgba(0,0,0,0.28) !important;
    border-color: rgba(255,255,255,0.22) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 2px 16px rgba(0,0,0,0.18);
}
#profile-root.has-theme .prof-hero {
    background: rgba(0,0,0,0.30) !important;
    border-color: rgba(255,255,255,0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 4px 24px rgba(0,0,0,0.2);
}
#profile-root.has-theme .prof-inner {
    background: rgba(0,0,0,0.18) !important;
    border-color: rgba(255,255,255,0.12) !important;
}
#profile-root.has-theme .prof-text-main { color: #ffffff !important; }
#profile-root.has-theme .prof-text-muted { color: rgba(255,255,255,0.55) !important; }
#profile-root.has-theme .prof-text-sub { color: rgba(255,255,255,0.3) !important; }
#profile-root.has-theme .prof-sep { border-color: rgba(255,255,255,0.08) !important; }
#profile-root.has-theme .prof-inline-bg { background: rgba(0,0,0,0.2) !important; border-color: rgba(255,255,255,0.1) !important; }
#profile-root.has-theme .prof-avatar-bg { background: rgba(0,0,0,0.45) !important; }
#profile-root.has-theme .prof-charm-bg { background: rgba(0,0,0,0.35) !important; border-color: rgba(255,255,255,0.15) !important; }
#profile-root.has-theme .prof-tab-border { border-color: rgba(255,255,255,0.2) !important; }
#profile-root.has-theme .tb { color: rgba(255,255,255,0.6) !important; }
#profile-root.has-theme .tb:hover { color: #fff !important; }
#profile-root.has-theme .tb.on { color: #fff !important; }
#profile-root.has-theme .sbar { background: rgba(255,255,255,0.12) !important; }
#profile-root.has-theme .prof-input { background: rgba(0,0,0,0.3) !important; border-color: rgba(255,255,255,0.15) !important; color: #fff !important; }
#profile-root.has-theme .prof-danger { background: rgba(127,29,29,0.4) !important; border-color: rgba(239,68,68,0.3) !important; }
#profile-root.has-theme .prof-shop-cat-off { background: rgba(0,0,0,0.2) !important; border-color: rgba(255,255,255,0.12) !important; color: rgba(255,255,255,0.5) !important; }
#profile-root.has-theme .prof-shop-cat-off:hover { color: #fff !important; }

html.dark  .prof-tab-border { border-color:rgba(255,255,255,.07); }
html:not(.dark) .prof-tab-border { border-color:#e2e8f0; }

html.dark  .prof-text-main { color:#ffffff; }
html:not(.dark) .prof-text-main { color:#0f172a; }

html.dark  .prof-text-muted { color:#475569; }
html:not(.dark) .prof-text-muted { color:#94a3b8; }

html.dark  .prof-text-sub { color:#334155; }
html:not(.dark) .prof-text-sub { color:#cbd5e1; }

html.dark  .prof-sep { border-color:rgba(255,255,255,.04); }
html:not(.dark) .prof-sep { border-color:#f1f5f9; }

html.dark  .prof-inline-bg { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.04); }
html:not(.dark) .prof-inline-bg { background:#f8fafc; border-color:#e2e8f0; }

html.dark  .prof-shop-cat-off  { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.07); color:#64748b; }
html:not(.dark) .prof-shop-cat-off { background:#f8fafc; border-color:#e2e8f0; color:#94a3b8; }
html.dark  .prof-shop-cat-off:hover { color:#fff; }
html:not(.dark) .prof-shop-cat-off:hover { color:#0f172a; }

html.dark  .prof-avatar-bg { background:#0d1117; }
html:not(.dark) .prof-avatar-bg { background:#f1f5f9; }

html.dark  .prof-charm-bg { background:#0d1117; border-color:rgba(255,255,255,.1); }
html:not(.dark) .prof-charm-bg { background:#f1f5f9; border-color:#e2e8f0; }

html.dark  .prof-activity-dot-border { border-color:#080b0f; }
html:not(.dark) .prof-activity-dot-border { border-color:#ffffff; }

/* Settings inputs */
html.dark  .prof-input  { background:rgba(0,0,0,.4); border-color:rgba(255,255,255,.1); color:#fff; }
html:not(.dark) .prof-input { background:#ffffff; border-color:#e2e8f0; color:#0f172a; }

/* Danger zone */
html.dark  .prof-danger { background:rgba(127,29,29,.2); border-color:rgba(239,68,68,.2); }
html:not(.dark) .prof-danger { background:#fff1f2; border-color:#fecaca; }

/* Blob tints */
html:not(.dark) .blob-1 { background:rgba(16,185,129,.06); }
html.dark        .blob-1 { background:rgba(16,185,129,.10); }
html:not(.dark) .blob-2 { background:rgba(203,213,225,.15); }
html.dark        .blob-2 { background:rgba(15,23,42,.30); }
</style>

@php
    $user = Auth::user();

    $totalBets   = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->count();
    $wonBets     = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->where('b.status','won')->count();
    $totalPayout = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->sum('b.payout');
    $winRate     = $totalBets > 0 ? round(($wonBets/$totalBets)*100) : 0;

    $ownedIds = DB::table('user_cosmetics')->where('user_id',$user->id)->pluck('cosmetic_id')->toArray();

    $equippedRows = DB::table('user_equipped as e')
        ->leftJoin('cosmetics as c', 'e.cosmetic_id', '=', 'c.id')
        ->where('e.user_id', $user->id)
        ->whereNotNull('c.id')
        ->select('e.slot','c.id','c.key','c.name','c.type','c.meta')
        ->get()
        ->keyBy('slot');

    $eFrame  = $equippedRows->get('frame');
    $eTitle  = $equippedRows->get('title');
    $eTheme  = $equippedRows->get('theme');
    $eCharms = collect(['charm_1','charm_2','charm_3'])->map(fn($s)=>$equippedRows->get($s))->filter();

    $frameMeta  = $eFrame ? json_decode($eFrame->meta, true) : null;
    $titleMeta  = $eTitle ? json_decode($eTitle->meta, true) : null;
    $themeMeta  = $eTheme ? json_decode($eTheme->meta, true) : null;
    $frameStyle = ($frameMeta && isset($frameMeta['gradient'])) ? "background:{$frameMeta['gradient']}" : '';
    $themeKey   = $themeMeta['bg_class'] ?? null;
    $themeClassMap = [
        'bg-default'  => 'bg-profile-default',
        'bg-midnight' => 'bg-profile-midnight',
        'bg-crimson'  => 'bg-profile-crimson',
        'bg-void'     => 'bg-profile-void',
    ];
    $themeClass = isset($themeKey) ? ($themeClassMap[$themeKey] ?? 'bg-profile-default') : 'bg-profile-default';
    $themeGradient = $themeMeta['gradient'] ?? null;

    $shop = \App\Models\Cosmetic::all()->groupBy('type');

    $recentBets = DB::table('wager_bets as b')
        ->join('wager_players as p','b.wager_player_id','=','p.id')
        ->join('wagers as w','b.wager_id','=','w.id')
        ->where('p.user_id',$user->id)
        ->select('w.name','b.bet_amount','b.payout','b.status')
        ->orderByDesc('b.updated_at')->limit(5)->get();
@endphp

<div class="pf min-h-screen {{ $themeClass }} prof-text-main{{ $themeGradient ? ' has-theme' : '' }}" id="profile-root" @if($themeGradient) style="background:{{ $themeGradient }}" @endif>

    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="blob-1 absolute top-0 left-1/3 w-[600px] h-[500px] rounded-full blur-[140px]"></div>
        <div class="blob-2 absolute bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12">

        {{-- ═══ HERO ═══ --}}
        <div class="fu prof-hero mb-8 rounded-3xl p-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.025]" style="background-image:repeating-linear-gradient(45deg,rgba(0,0,0,.3) 0,rgba(0,0,0,.3) 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-8">

                <div class="relative shrink-0">
                    <div class="av-ring rounded-[22px]" id="hero-ring" style="{{ $frameStyle ?: '' }}; width:112px; height:112px;">
                        <div class="prof-avatar-bg rounded-[19px] flex items-center justify-center" style="width:100%;height:100%;">
                            <span class="dsp text-5xl prof-text-main">{{ strtoupper(substr($user->name,0,1)) }}</span>
                        </div>
                    </div>
                    @if($eCharms->isNotEmpty())
                    <div class="absolute -bottom-2 -right-2 flex gap-1" id="hero-charms">
                        @foreach($eCharms as $ch)
                        @php $cm=json_decode($ch->meta,true); @endphp
                        <div class="prof-charm-bg w-8 h-8 rounded-lg flex items-center justify-center text-sm border">{{ $cm['emoji']??'?' }}</div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <h1 class="dsp text-5xl prof-text-main">{{ $user->name }}</h1>
                        <div id="hero-title">
                            @if($eTitle && $titleMeta)
                            <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="mn text-xs prof-text-muted mb-5">Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}</p>
                    <div class="flex flex-wrap gap-7">
                        @foreach([
                            ['Win Rate', $winRate.'%',             'text-emerald-600 dark:text-white'],
                            ['Bets',     $totalBets,               'prof-text-main'],
                            ['Won',      $wonBets,                 'text-emerald-600 dark:text-emerald-400'],
                            ['Earned',   number_format($totalPayout,0), 'text-amber-600 dark:text-amber-400'],
                        ] as [$l,$v,$c])
                        <div>
                            <p class="mn text-xs prof-text-muted mb-0.5">{{ $l }}</p>
                            <p class="dsp text-2xl {{ $c }}">{{ $v }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="shrink-0 text-right">
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 bpulse">
                        <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-6 h-6 dark:invert">
                        <span class="dsp text-2xl text-amber-600 dark:text-amber-400" id="bal">{{ number_format($user->balance,0) }}</span>
                    </div>
                    <p class="mn text-xs prof-text-muted mt-1">coins</p>
                </div>
            </div>
        </div>

        {{-- ═══ TABS ═══ --}}
        <div class="fu prof-tab-border border-b mb-6 flex" style="animation-delay:50ms">
            @foreach(['stats'=>'Stats','shop'=>'🛍 Shop','customize'=>'🎨 Customize','settings'=>'Settings'] as $t=>$l)
            <button onclick="switchTab('{{ $t }}')" class="tb {{ $t==='stats'?'on':'' }}" id="tb-{{ $t }}">{{ $l }}</button>
            @endforeach
        </div>

        {{-- ═══ STATS ═══ --}}
        <div class="tc on" id="tc-stats">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 space-y-5">

                    <div class="prof-card p-6">
                        <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-5">Performance</p>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            @foreach([
                                ['Won',   $wonBets,              'text-emerald-600 dark:text-emerald-400'],
                                ['Lost',  $totalBets-$wonBets,   'text-red-500 dark:text-red-400'],
                                ['Total', $totalBets,            'prof-text-main'],
                            ] as [$l,$v,$c])
                            <div class="prof-inner rounded-xl border p-4 text-center">
                                <p class="dsp text-4xl {{ $c }}">{{ $v }}</p>
                                <p class="mn text-xs prof-text-muted mt-1">{{ $l }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mb-1.5">
                            <span class="mn text-xs prof-text-muted">Win Rate</span>
                            <span class="mn text-xs text-emerald-600 dark:text-emerald-400">{{ $winRate }}%</span>
                        </div>
                        <div class="sbar"><div class="sfil" style="width:{{ $winRate }}%"></div></div>
                    </div>

                    <div class="prof-card p-6">
                        <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Recent Activity</p>
                        @forelse($recentBets as $b)
                        <div class="flex items-center justify-between py-3 border-b prof-sep last:border-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-2 h-2 rounded-full shrink-0 {{ $b->status==='won'?'bg-emerald-500':($b->status==='lost'?'bg-red-500':'bg-slate-400') }}"></div>
                                <span class="text-sm font-bold prof-text-main truncate">{{ $b->name }}</span>
                            </div>
                            <span class="mn text-xs font-bold ml-4 shrink-0 {{ $b->status==='won'?'text-emerald-600 dark:text-emerald-400':'text-red-500 dark:text-red-400' }}">
                                {{ $b->status==='won' ? '+'.number_format($b->payout-$b->bet_amount,0) : '-'.number_format($b->bet_amount,0) }}
                            </span>
                        </div>
                        @empty
                        <p class="prof-text-muted text-sm text-center py-6">No bets yet</p>
                        @endforelse
                        <a href="{{ route('history') }}" class="block text-center mn text-xs text-emerald-600 dark:text-emerald-500 hover:text-emerald-500 dark:hover:text-emerald-400 mt-4 transition-colors">View full history →</a>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="prof-card p-5">
                        <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Equipped</p>
                        <div class="space-y-2">
                            @foreach(['frame'=>'Frame','title'=>'Title','theme'=>'Theme','charm_1'=>'Charm 1','charm_2'=>'Charm 2','charm_3'=>'Charm 3'] as $slot=>$label)
                            @php $eq=$equippedRows->get($slot); @endphp
                            <div class="prof-inline-bg flex items-center justify-between py-2 px-3 rounded-xl border">
                                <span class="mn text-xs prof-text-muted">{{ $label }}</span>
                                <span class="text-xs font-bold {{ $eq?'text-emerald-600 dark:text-emerald-400':'prof-text-sub' }}" data-equipped-slot="{{ $slot }}">{{ $eq?$eq->name:'—' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
                        <p class="mn text-xs uppercase tracking-[.2em] text-amber-600/60 mb-2">All-time Earned</p>
                        <p class="dsp text-5xl text-amber-600 dark:text-amber-400">{{ number_format($totalPayout,0) }}</p>
                        <p class="mn text-xs prof-text-muted mt-1">coins won</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SHOP ═══ --}}
        <div class="tc" id="tc-shop">
            <div class="flex items-center gap-2 mb-6 flex-wrap">
                @foreach(['frame'=>'🖼 Frames','title'=>'👑 Titles','theme'=>'🎨 Themes','charm'=>'✨ Charms'] as $cat=>$lbl)
                <button onclick="shopCat('{{ $cat }}')" id="sc-{{ $cat }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-200 {{ $cat==='frame'?'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400':'prof-shop-cat-off' }}">
                    {{ $lbl }}
                </button>
                @endforeach
                <div class="ml-auto flex items-center gap-1 p-1 bg-slate-100 dark:bg-white/[0.04] border border-slate-200 dark:border-white/[0.07] rounded-xl">
                    <button onclick="sortShop('asc')" id="sort-asc"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white dark:bg-white/[0.08] text-slate-700 dark:text-white border border-slate-200 dark:border-white/[0.1] shadow-sm"
                        title="Cheapest first">↑ Price</button>
                    <button onclick="sortShop('desc')" id="sort-desc"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white"
                        title="Most expensive first">↓ Price</button>
                </div>
            </div>

            @foreach(['frame','title','theme','charm'] as $type)
            @php $availableItems = $shop->get($type, collect())->filter(fn($i) => !in_array($i->id, $ownedIds)); @endphp
            <div id="sc-c-{{ $type }}" class="{{ $type!=='frame'?'hidden':'' }}">
                @if($availableItems->isEmpty())
                <div class="py-16 text-center">
                    <div class="text-3xl mb-3">✓</div>
                    <p class="font-bold prof-text-main mb-1">You own everything here!</p>
                    <p class="text-sm prof-text-muted">Check the Customize tab to equip your items.</p>
                </div>
                @else
                <div class="grid {{ $type==='charm'?'grid-cols-3 md:grid-cols-6':($type==='title'?'grid-cols-1 md:grid-cols-2':'grid-cols-2 md:grid-cols-4') }} gap-4" id="shop-grid-{{ $type }}">
                    @foreach($availableItems->sortBy('price') as $item)
                    @php $m = $item->meta ?? []; @endphp
                    <div class="ccard prof-card rounded-2xl p-4 relative" data-price="{{ $item->price }}" data-ctype="{{ $type }}"
                        data-item="{{ htmlspecialchars(json_encode(['id'=>$item->id,'name'=>$item->name,'type'=>$type,'gradient'=>$m['gradient']??'','emoji'=>$m['emoji']??'','bg'=>$m['bg']??'','color'=>$m['color']??'','bg_class'=>$m['bg_class']??'']), ENT_QUOTES) }}">

                        @if($type==='frame')
                        <div class="w-14 h-14 mx-auto mb-3 p-[3px]" style="background:{{ $m['gradient']??'#333' }}; border-radius:16px;">
                            <div class="prof-avatar-bg w-full h-full flex items-center justify-center" style="border-radius:13px;">
                                <span class="dsp text-2xl prof-text-main">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            </div>
                        </div>
                        @elseif($type==='theme')
                        <div class="h-14 w-full rounded-xl mb-3" style="background:{{ $m['gradient']??'#333' }}"></div>
                        @elseif($type==='charm')
                        <div class="text-4xl text-center mb-2">{{ $m['emoji']??'?' }}</div>
                        @elseif($type==='title')
                        <div class="mb-3"><span class="ttag {{ $m['bg']??'bg-slate-500/10 border-slate-500/30' }} {{ $m['color']??'text-slate-500' }}">{{ $item->name }}</span></div>
                        @endif

                        <p class="font-bold text-sm prof-text-main text-center mb-0.5">{{ $item->name }}</p>
                        <p class="mn text-xs text-center r-{{ $item->rarity }} mb-3">{{ ucfirst($item->rarity) }}</p>

                        <button class="w-full py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 transition-all active:scale-95"
                                onclick="buyItem({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }},this)">
                            <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 inline dark:invert"> {{ number_format($item->price) }}
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- ═══ CUSTOMIZE ═══ --}}
        <div class="tc" id="tc-customize">
            @php
                $ownedFrames = $shop->get('frame',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedTitles = $shop->get('title',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedThemes = $shop->get('theme',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedCharms = $shop->get('charm',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Live preview --}}
                <div class="prof-card p-6 rounded-2xl">
                    <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Preview</p>
                    <div class="prof-inner rounded-2xl border p-10 flex flex-col items-center gap-4">
                        <div class="relative">
                            <div class="av-ring rounded-[20px]" id="prv-ring" style="{{ $frameStyle ?: '' }}; width:80px; height:80px;">
                                <div class="prof-avatar-bg rounded-[17px] flex items-center justify-center" style="width:100%;height:100%;">
                                    <span class="dsp text-3xl prof-text-main">{{ strtoupper(substr($user->name,0,1)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="dsp text-2xl prof-text-main">{{ $user->name }}</p>
                            <div id="prv-title" class="mt-1 min-h-[24px]">
                                @if($eTitle && $titleMeta)
                                <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 min-h-[40px]" id="prv-charms">
                            @foreach($eCharms as $ch)
                            @php $cm=json_decode($ch->meta,true); @endphp
                            <div class="prof-charm-bg w-9 h-9 rounded-xl border flex items-center justify-center text-lg">{{ $cm['emoji']??'?' }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Frame --}}
                    <div class="prof-card p-5 rounded-2xl">
                        <p class="mn text-xs uppercase tracking-[.15em] prof-text-muted mb-3">Frame</p>
                        <p id="cust-frame-none" class="text-xs prof-text-muted {{ $ownedFrames->isNotEmpty() ? 'hidden' : '' }}">None owned — <button onclick="switchTab('shop');shopCat('frame')" class="text-emerald-600 dark:text-emerald-500 hover:underline">browse shop</button></p>
                        <div id="cust-frame-list" class="flex gap-2 flex-wrap {{ $ownedFrames->isEmpty() ? 'hidden' : '' }}">
                            <button onclick="equipItem('frame',null,null,this)" class="w-10 h-10 rounded-xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-400 dark:hover:border-white/20 transition-all prof-text-muted text-xs font-bold">✕</button>
                            @foreach($ownedFrames as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipItem('frame',{{ $item->id }},'{{ $m['gradient']??'' }}',this)"
                                    class="w-10 h-10 rounded-xl border-2 transition-all {{ $eq?'border-emerald-500 shadow-[0_0_0_1px_#10b981]':'border-transparent hover:border-slate-300 dark:hover:border-white/20' }}"
                                    style="background:{{ $m['gradient']??'#333' }}" title="{{ $item->name }}"></button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="prof-card p-5 rounded-2xl">
                        <p class="mn text-xs uppercase tracking-[.15em] prof-text-muted mb-3">Title</p>
                        <p id="cust-title-none" class="text-xs prof-text-muted {{ $ownedTitles->isNotEmpty() ? 'hidden' : '' }}">None owned — <button onclick="switchTab('shop');shopCat('title')" class="text-emerald-600 dark:text-emerald-500 hover:underline">browse shop</button></p>
                        <div id="cust-title-list" class="flex gap-2 flex-wrap {{ $ownedTitles->isEmpty() ? 'hidden' : '' }}">
                            <button onclick="equipTitle(null,null,null)" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-xs prof-text-muted font-bold hover:border-slate-400 dark:hover:border-white/20 transition-all">None</button>
                            @foreach($ownedTitles as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipTitle({{ $item->id }},'{{ $m['bg']??'' }}','{{ $m['color']??'' }}','{{ addslashes($item->name) }}')"
                                    class="ttag {{ $m['bg']??'' }} {{ $m['color']??'' }} cursor-pointer hover:opacity-80 transition-all {{ $eq?'ring-2 ring-emerald-500':'' }}">
                                {{ $item->name }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Theme --}}
                    <div class="prof-card p-5 rounded-2xl">
                        <p class="mn text-xs uppercase tracking-[.15em] prof-text-muted mb-3">Theme</p>
                        <p id="cust-theme-none" class="text-xs prof-text-muted {{ $ownedThemes->isNotEmpty() ? 'hidden' : '' }}">None owned — <button onclick="switchTab('shop');shopCat('theme')" class="text-emerald-600 dark:text-emerald-500 hover:underline">browse shop</button></p>
                        <div id="cust-theme-list" class="flex gap-2 flex-wrap {{ $ownedThemes->isEmpty() ? 'hidden' : '' }}">
                            <button onclick="equipTheme(null,null,null)" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-xs prof-text-muted font-bold hover:border-slate-400 dark:hover:border-white/20 transition-all">Default</button>
                            @foreach($ownedThemes as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipTheme({{ $item->id }},'{{ $m['bg_class']??'' }}','{{ addslashes($m['gradient']??'') }}')"
                                    class="px-3 py-1.5 rounded-lg border-2 text-xs font-bold transition-all {{ $eq?'border-emerald-500 text-emerald-600 dark:text-emerald-400':'border-slate-200 dark:border-white/10 prof-text-muted hover:border-slate-400 dark:hover:border-white/20' }}"
                                    style="background:{{ $m['gradient']??'#1e293b' }}">{{ $item->name }}</button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Charms --}}
                    <div class="prof-card p-5 rounded-2xl">
                        <p class="mn text-xs uppercase tracking-[.15em] prof-text-muted mb-3">Charms <span class="tracking-normal text-[11px] font-normal prof-text-muted">(3 slots)</span></p>
                        <p id="cust-charm-none" class="text-xs prof-text-muted {{ $ownedCharms->isNotEmpty() ? 'hidden' : '' }}">None owned — <button onclick="switchTab('shop');shopCat('charm')" class="text-emerald-600 dark:text-emerald-500 hover:underline">browse shop</button></p>
                        <div id="cust-charm-slots" class="{{ $ownedCharms->isEmpty() ? 'hidden' : '' }}">
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                @foreach(['charm_1','charm_2','charm_3'] as $slot)
                                @php $eq=$equippedRows->get($slot); $cm=$eq?json_decode($eq->meta,true):null; @endphp
                                <div id="charm-slot-{{ $slot }}" class="prof-card rounded-xl border p-3 text-center {{ $eq?'border-emerald-500/40 bg-emerald-500/5':'' }}">
                                    <p class="mn text-[10px] prof-text-muted mb-1">{{ strtoupper(str_replace('_',' ',$slot)) }}</p>
                                    <div class="text-2xl mb-1.5 charm-emoji">{{ $cm?$cm['emoji']:'—' }}</div>
                                    <button onclick="equipCharm('{{ $slot }}',null)" class="charm-remove mn text-[10px] text-red-500 hover:text-red-400 transition-colors" {{ $eq?'':'style=display:none' }}>remove</button>
                                </div>
                                @endforeach
                            </div>
                            <div id="cust-charm-palette" class="flex gap-2 flex-wrap">
                                @foreach($ownedCharms as $item)
                                @php $m=$item->meta??[]; @endphp
                                <button onclick="pickCharm({{ $item->id }},'{{ $m['emoji']??'?' }}')"
                                        class="prof-card w-10 h-10 rounded-xl border hover:border-emerald-500/40 transition-all text-xl flex items-center justify-center"
                                        title="{{ $item->name }}">{{ $m['emoji']??'?' }}</button>
                                @endforeach
                            </div>
                            <p class="mn text-xs prof-text-muted mt-2">Tap to fill next empty slot</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SETTINGS ═══ --}}
        <div class="tc" id="tc-settings">
            <div class="max-w-lg space-y-4">
                @if(session('success'))
                <div class="px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm mn">✓ {{ session('success') }}</div>
                @endif
                @foreach([
                    ['Username','name','text',   route('profile.change-username'),$user->name],
                    ['Email',   'email','email', route('profile.change-email'),   $user->email],
                ] as [$label,$fname,$ftype,$faction,$fval])
                <div class="prof-card rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b prof-sep"><p class="mn text-xs uppercase tracking-[.15em] prof-text-muted">{{ $label }}</p></div>
                    <div class="px-6 py-5">
                        <form action="{{ $faction }}" method="POST" class="flex gap-3">@csrf
                            <input type="{{ $ftype }}" name="{{ $fname }}" value="{{ old($fname,$fval) }}"
                                   class="prof-input flex-1 border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 transition-all"/>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all active:scale-95">Save</button>
                        </form>
                        @error($fname)<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                @endforeach
                <div class="prof-danger rounded-2xl overflow-hidden border">
                    <div class="px-6 py-4 border-b border-red-200 dark:border-red-500/10"><p class="mn text-xs uppercase tracking-[.15em] text-red-500/80">Danger Zone</p></div>
                    <div class="px-6 py-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold prof-text-main">Delete Account</p>
                            <p class="text-xs prof-text-muted mt-0.5">Permanently removes everything.</p>
                        </div>
                        <button class="px-4 py-2 bg-red-50 dark:bg-red-950/60 hover:bg-red-100 dark:hover:bg-red-900/60 border border-red-300 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm font-bold rounded-xl transition-all active:scale-95">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="toast" id="toast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const themeClassMap = {
    'bg-default':  'bg-profile-default',
    'bg-midnight': 'bg-profile-midnight',
    'bg-crimson':  'bg-profile-crimson',
    'bg-void':     'bg-profile-void',
};

let state = {
    frame:   { id: {{ $eFrame ? $eFrame->id : 'null' }}, gradient: '{{ $frameMeta ? ($frameMeta["gradient"] ?? "") : "" }}' },
    title:   { id: {{ $eTitle ? $eTitle->id : 'null' }}, bg: '{{ $titleMeta ? ($titleMeta["bg"] ?? "") : "" }}', color: '{{ $titleMeta ? ($titleMeta["color"] ?? "") : "" }}', name: '{{ $eTitle ? addslashes($eTitle->name) : "" }}' },
    theme:   { id: {{ $eTheme ? $eTheme->id : 'null' }}, bgClass: '{{ $themeClass }}', gradient: '{{ $themeGradient ?? '' }}' },
    charm_1: { id: {{ $equippedRows->get('charm_1') ? $equippedRows->get('charm_1')->id : 'null' }}, emoji: '{{ optional($equippedRows->get('charm_1') ? json_decode($equippedRows->get('charm_1')->meta,true) : null)["emoji"] ?? "" }}' },
    charm_2: { id: {{ $equippedRows->get('charm_2') ? $equippedRows->get('charm_2')->id : 'null' }}, emoji: '{{ optional($equippedRows->get('charm_2') ? json_decode($equippedRows->get('charm_2')->meta,true) : null)["emoji"] ?? "" }}' },
    charm_3: { id: {{ $equippedRows->get('charm_3') ? $equippedRows->get('charm_3')->id : 'null' }}, emoji: '{{ optional($equippedRows->get('charm_3') ? json_decode($equippedRows->get('charm_3')->meta,true) : null)["emoji"] ?? "" }}' },
};

function switchTab(t) {
    document.querySelectorAll('.tb').forEach(b => b.classList.remove('on'));
    document.querySelectorAll('.tc').forEach(c => c.classList.remove('on'));
    document.getElementById('tb-'+t).classList.add('on');
    document.getElementById('tc-'+t).classList.add('on');
}

function shopCat(cat) {
    ['frame','title','theme','charm'].forEach(c => {
        const on = c===cat;
        const btn = document.getElementById('sc-'+c);
        btn.className = `px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-200 ${on
            ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400'
            : 'prof-shop-cat-off'}`;
        document.getElementById('sc-c-'+c).classList.toggle('hidden',!on);
    });
}

let shopSortOrder = 'asc';
function sortShop(order) {
    shopSortOrder = order;
    document.getElementById('sort-asc').className  = `px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${order==='asc'  ? 'bg-white dark:bg-white/[0.08] text-slate-700 dark:text-white border border-slate-200 dark:border-white/[0.1] shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white'}`;
    document.getElementById('sort-desc').className = `px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${order==='desc' ? 'bg-white dark:bg-white/[0.08] text-slate-700 dark:text-white border border-slate-200 dark:border-white/[0.1] shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white'}`;
    ['frame','title','theme','charm'].forEach(type => {
        const grid = document.getElementById('shop-grid-'+type);
        if (!grid) return;
        const cards = Array.from(grid.querySelectorAll('[data-price]'));
        cards.sort((a, b) => order === 'asc'
            ? parseInt(a.dataset.price) - parseInt(b.dataset.price)
            : parseInt(b.dataset.price) - parseInt(a.dataset.price));
        cards.forEach(c => grid.appendChild(c));
    });
}

function toast(msg, type='ok') {
    const el = document.getElementById('toast');
    el.textContent = msg; el.className = `toast ${type}`;
    setTimeout(() => el.classList.add('show'), 10);
    setTimeout(() => el.classList.remove('show'), 3000);
}

async function post(url, body) {
    const r = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:JSON.stringify(body) });
    return r.json();
}

function applyFrame(gradient) {
    const bg = gradient || '';
    document.getElementById('hero-ring').style.background = bg;
    document.getElementById('prv-ring').style.background  = bg;
}

function applyTitle(id, bg, color, name) {
    const html = id && name ? `<span class="ttag ${bg} ${color}">${name}</span>` : '';
    document.getElementById('hero-title').innerHTML = html;
    document.getElementById('prv-title').innerHTML  = html;
}

function applyCharms() {
    const slots  = ['charm_1','charm_2','charm_3'];
    const charms = slots.map(s => state[s].emoji).filter(Boolean);
    const heroBadges = document.getElementById('hero-charms');
    if (heroBadges) {
        heroBadges.innerHTML = charms.map(e =>
            `<div class="prof-charm-bg w-8 h-8 rounded-lg border flex items-center justify-center text-sm">${e}</div>`
        ).join('');
    }
    document.getElementById('prv-charms').innerHTML = charms.map(e =>
        `<div class="prof-charm-bg w-9 h-9 rounded-xl border flex items-center justify-center text-lg">${e}</div>`
    ).join('');
}

function applyEquippedSidebar() {
    const map = {
        frame:   state.frame.id   ? (document.querySelector(`#card-${state.frame.id} .font-bold.text-sm`)?.textContent ?? '—') : '—',
        title:   state.title.id   ? state.title.name  : '—',
        theme:   state.theme.id   ? (document.querySelector(`#card-${state.theme.id} .font-bold.text-sm`)?.textContent ?? '—') : '—',
        charm_1: state.charm_1.id ? state.charm_1.emoji : '—',
        charm_2: state.charm_2.id ? state.charm_2.emoji : '—',
        charm_3: state.charm_3.id ? state.charm_3.emoji : '—',
    };
    document.querySelectorAll('[data-equipped-slot]').forEach(el => {
        const val = map[el.dataset.equippedSlot] ?? '—';
        el.textContent = val;
        el.className = `text-xs font-bold ${val !== '—' ? 'text-emerald-600 dark:text-emerald-400' : 'prof-text-sub'}`;
    });
}

function markEquippedCard(id, type) {
    document.querySelectorAll(`[data-ctype="${type}"]`).forEach(card => {
        card.classList.remove('is-equipped');
        card.style.borderColor = '';
        card.style.boxShadow = '';
        const btn = card.querySelector('.eq-btn');
        if (btn) { btn.classList.remove('eq-on'); btn.classList.add('eq-off'); btn.textContent = 'Equip'; }
    });
    if (!id) return;
    const card = document.getElementById('card-'+id);
    if (!card) return;
    card.classList.add('is-equipped');
    card.style.borderColor = 'rgba(16,185,129,.6)';
    card.style.boxShadow   = '0 0 0 2px #10b981,0 0 18px rgba(16,185,129,.2)';
    const btn = card.querySelector('.eq-btn');
    if (btn) { btn.classList.add('eq-on'); btn.classList.remove('eq-off'); btn.textContent = '✓ Equipped'; }
}

let pendingBuy = null;

function buyItem(id, name, price, btn) {
    if (pendingBuy) {
        if (pendingBuy.id === id) {
            clearBuyConfirm();
            executeBuy(id, price, btn);
            return;
        }
        clearBuyConfirm();
    }
    pendingBuy = { id, btn, html: btn.innerHTML, cls: btn.className };
    btn.innerHTML = `✓ Confirm — <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" class="w-3.5 h-3.5 inline align-middle dark:invert"> ${Number(price).toLocaleString()}`;
    btn.className = btn.className
        .replace('bg-amber-500/10','bg-emerald-500/10')
        .replace('border-amber-500/20','border-emerald-500/40')
        .replace('text-amber-600','text-emerald-600')
        .replace('hover:bg-amber-500/20','hover:bg-emerald-500/20');
    setTimeout(() => document.addEventListener('click', buyOutsideClick), 0);
}

function clearBuyConfirm() {
    if (!pendingBuy) return;
    pendingBuy.btn.innerHTML  = pendingBuy.html;
    pendingBuy.btn.className  = pendingBuy.cls;
    pendingBuy = null;
    document.removeEventListener('click', buyOutsideClick);
}

function buyOutsideClick(e) {
    if (pendingBuy && !pendingBuy.btn.contains(e.target)) clearBuyConfirm();
}

async function executeBuy(id, price, btn) {
    btn.disabled = true;
    btn.innerHTML = '<span class="opacity-50 tracking-wide">Buying…</span>';
    const originalHtml = `<img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 inline dark:invert"> ${Number(price).toLocaleString()}`;
    try {
        const r = await fetch('/cosmetics/buy', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify({cosmetic_id: id})
        });
        const data = await r.json();
        if (data.success) {
            toast('✓ ' + data.message);
            const balEl = document.getElementById('bal');
            if (balEl) balEl.textContent = Number(data.balance).toLocaleString();
            const card = btn.closest('[data-item]');
            if (card) {
                try { addToCustomize(JSON.parse(card.dataset.item)); } catch(_) {}
                card.style.transition = 'opacity .25s, transform .25s';
                card.style.opacity = '0';
                card.style.transform = 'scale(.92)';
                const ctype = card.dataset.ctype;
                setTimeout(() => { card.remove(); checkShopEmpty(ctype); }, 250);
            }
        } else {
            toast(data.message || 'Purchase failed.', 'err');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    } catch (e) {
        toast('Something went wrong. Please refresh.', 'err');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
}

function addToCustomize(item) {
    const noneEl = document.getElementById(`cust-${item.type}-none`);
    const listEl = document.getElementById(`cust-${item.type}-list`);

    if (item.type === 'charm') {
        const slotsEl  = document.getElementById('cust-charm-slots');
        const palette  = document.getElementById('cust-charm-palette');
        if (noneEl) noneEl.classList.add('hidden');
        if (slotsEl) slotsEl.classList.remove('hidden');
        if (palette) {
            const btn = document.createElement('button');
            btn.className = 'prof-card w-10 h-10 rounded-xl border hover:border-emerald-500/40 transition-all text-xl flex items-center justify-center';
            btn.title = item.name;
            btn.textContent = item.emoji || '?';
            btn.onclick = () => pickCharm(item.id, item.emoji);
            palette.appendChild(btn);
        }
        return;
    }

    if (!listEl) return;
    if (noneEl) noneEl.classList.add('hidden');
    listEl.classList.remove('hidden');

    const btn = document.createElement('button');
    if (item.type === 'frame') {
        btn.className = 'w-10 h-10 rounded-xl border-2 transition-all border-transparent hover:border-slate-300 dark:hover:border-white/20';
        btn.style.background = item.gradient || '#333';
        btn.title = item.name;
        btn.onclick = function() { equipItem('frame', item.id, item.gradient, this); };
    } else if (item.type === 'title') {
        btn.className = `ttag ${item.bg} ${item.color} cursor-pointer hover:opacity-80 transition-all`;
        btn.textContent = item.name;
        btn.onclick = () => equipTitle(item.id, item.bg, item.color, item.name);
    } else if (item.type === 'theme') {
        btn.className = 'px-3 py-1.5 rounded-lg border-2 text-xs font-bold transition-all border-slate-200 dark:border-white/10 prof-text-muted hover:border-slate-400 dark:hover:border-white/20';
        btn.style.background = item.gradient || '#1e293b';
        btn.textContent = item.name;
        btn.onclick = () => equipTheme(item.id, item.bg_class, item.gradient);
    }
    listEl.appendChild(btn);
}

function checkShopEmpty(type) {
    const grid = document.getElementById('shop-grid-' + type);
    if (!grid || grid.querySelectorAll('[data-price]').length > 0) return;
    grid.parentElement.innerHTML = `
        <div class="py-16 text-center">
            <div class="text-3xl mb-3">✓</div>
            <p class="font-bold prof-text-main mb-1">You own everything here!</p>
            <p class="text-sm prof-text-muted">Check the Customize tab to equip your items.</p>
        </div>`;
}

async function toggleEquip(id, type, isEquipped, btn) {
    const slotMap = {frame:'frame',title:'title',theme:'theme',charm:'charm_1'};
    const slot = slotMap[type];
    const data = await post('/cosmetics/equip', {slot, cosmetic_id: isEquipped ? null : id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    if (isEquipped) state[slot] = {id:null, gradient:'', emoji:'', name:'', bg:'', color:''};
    markEquippedCard(isEquipped ? null : id, type);
    if (type==='frame') applyFrame(isEquipped ? null : state.frame.gradient);
    if (type==='title') applyTitle(isEquipped ? null : id, state.title.bg, state.title.color, state.title.name);
    if (type==='charm') applyCharms();
    applyEquippedSidebar();
}

async function equipItem(slot, id, gradient, btn) {
    const data = await post('/cosmetics/equip', {slot, cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state.frame = {id, gradient};
    applyFrame(gradient);
    markEquippedCard(id, 'frame');
    applyEquippedSidebar();
}

async function equipTitle(id, bg, color, name) {
    const data = await post('/cosmetics/equip', {slot:'title', cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state.title = {id, bg, color, name};
    applyTitle(id, bg, color, name);
    markEquippedCard(id, 'title');
    applyEquippedSidebar();
}

async function equipTheme(id, bgClass, gradient) {
    const data = await post('/cosmetics/equip', {slot:'theme', cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state.theme = {id, bgClass, gradient};
    const root = document.getElementById('profile-root');
    Object.values(themeClassMap).forEach(c => root.classList.remove(c));
    if (gradient) {
        root.style.background = gradient;
        root.classList.add('has-theme');
    } else {
        root.style.background = '';
        root.classList.remove('has-theme');
        const mapped = themeClassMap[bgClass] || (bgClass ? bgClass : null);
        root.classList.add(mapped || 'bg-profile-default');
    }
    markEquippedCard(id, 'theme');
    applyEquippedSidebar();
}

async function equipCharm(slot, id) {
    const data = await post('/cosmetics/equip', {slot, cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state[slot] = {id: null, emoji: ''};
    applyCharms();
    applyEquippedSidebar();
    refreshCharmSlots();
}

async function pickCharm(id, emoji) {
    const slot = ['charm_1','charm_2','charm_3'].find(s => !state[s].id);
    if (!slot) { toast('All charm slots full — remove one first','err'); return; }
    const data = await post('/cosmetics/equip', {slot, cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state[slot] = {id, emoji};
    applyCharms();
    applyEquippedSidebar();
    refreshCharmSlots();
}

function refreshCharmSlots() {
    ['charm_1','charm_2','charm_3'].forEach(slot => {
        const el = document.getElementById('charm-slot-'+slot);
        if (!el) return;
        const s = state[slot];
        el.querySelector('.charm-emoji').textContent = s.emoji || '—';
        const removeBtn = el.querySelector('.charm-remove');
        if (s.id) {
            el.classList.add('border-emerald-500/40','bg-emerald-500/5');
            if (removeBtn) removeBtn.style.display='';
        } else {
            el.classList.remove('border-emerald-500/40','bg-emerald-500/5');
            if (removeBtn) removeBtn.style.display='none';
        }
    });
}

window.addEventListener('load', () => {
    document.querySelectorAll('.sfil').forEach(b => {
        const w = b.style.width; b.style.width='0';
        setTimeout(()=>b.style.width=w, 400);
    });
});
</script>
</x-app-layout>