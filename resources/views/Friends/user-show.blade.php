<x-app-layout>
@php
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
    $eCharms = collect(['charm_1','charm_2','charm_3'])->map(fn($s) => $equippedRows->get($s))->filter();

    $frameMeta  = $eFrame  ? json_decode($eFrame->meta, true)  : null;
    $titleMeta  = $eTitle  ? json_decode($eTitle->meta, true)  : null;
    $themeMeta  = $eTheme  ? json_decode($eTheme->meta, true)  : null;
    $frameStyle = ($frameMeta && isset($frameMeta['gradient'])) ? "background:{$frameMeta['gradient']}" : '';
    $themeKey   = $themeMeta['bg_class'] ?? null;
    $themeClassMap = [
        'bg-default'  => 'bg-profile-default',
        'bg-midnight' => 'bg-profile-midnight',
        'bg-crimson'  => 'bg-profile-crimson',
        'bg-void'     => 'bg-profile-void',
    ];
    $themeClass = isset($themeKey) ? ($themeClassMap[$themeKey] ?? 'bg-profile-default') : 'bg-profile-default';

    $totalBets   = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->count();
    $wonBets     = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->where('b.status','won')->count();
    $totalPayout = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->sum('b.payout');
    $winRate     = $totalBets > 0 ? round(($wonBets / $totalBets) * 100) : 0;

    $recentBets = DB::table('wager_bets as b')
        ->join('wager_players as p','b.wager_player_id','=','p.id')
        ->join('wagers as w','b.wager_id','=','w.id')
        ->where('p.user_id',$user->id)
        ->select('w.name','b.bet_amount','b.payout','b.status')
        ->orderByDesc('b.updated_at')->limit(5)->get();
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cabinet+Grotesk:wght@400;700;800;900&family=DM+Mono:wght@400;500&display=swap');

.pf  { font-family:'Cabinet Grotesk',sans-serif; }
.dsp { font-family:'Bebas Neue',sans-serif; letter-spacing:0.05em; }
.mn  { font-family:'DM Mono',monospace; }

.fu { opacity:0; transform:translateY(14px); animation:fu .4s ease forwards; }
@keyframes fu { to { opacity:1; transform:translateY(0); } }

.av-ring { display:block; padding:3px; }

/* ── Theme backgrounds ── */
html.dark        .bg-profile-default  { background:#080b0f; }
html.dark        .bg-profile-midnight { background:linear-gradient(160deg,#0f0c29 0%,#1a1640 100%); }
html.dark        .bg-profile-crimson  { background:linear-gradient(160deg,#1a0505 0%,#2d0a0a 100%); }
html.dark        .bg-profile-void     { background:linear-gradient(160deg,#050510 0%,#0d0520 100%); }
html:not(.dark)  .bg-profile-default  { background:#f8fafc; }
html:not(.dark)  .bg-profile-midnight { background:linear-gradient(160deg,#ede9fe 0%,#e0e7ff 100%); }
html:not(.dark)  .bg-profile-crimson  { background:linear-gradient(160deg,#fff1f2 0%,#ffe4e6 100%); }
html:not(.dark)  .bg-profile-void     { background:linear-gradient(160deg,#f5f3ff 0%,#ede9fe 100%); }

/* ── Semantic colour tokens ── */
html.dark        .prof-text-main  { color:#ffffff; }
html:not(.dark)  .prof-text-main  { color:#0f172a; }
html.dark        .prof-text-muted { color:#475569; }
html:not(.dark)  .prof-text-muted { color:#94a3b8; }
html.dark        .prof-text-sub   { color:#334155; }
html:not(.dark)  .prof-text-sub   { color:#cbd5e1; }

/* ── Cards ── */
html.dark        .prof-card   { background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.07); }
html:not(.dark)  .prof-card   { background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,.06); }
html.dark        .prof-hero   { background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.08); }
html:not(.dark)  .prof-hero   { background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,.06); }
html.dark        .prof-inner  { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.05); }
html:not(.dark)  .prof-inner  { background:#f8fafc; border:1px solid #e2e8f0; }

/* ── Inline row items ── */
html.dark        .prof-inline-bg { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.04); }
html:not(.dark)  .prof-inline-bg { background:#f8fafc; border-color:#e2e8f0; }

/* ── Separators ── */
html.dark        .prof-sep { border-color:rgba(255,255,255,.04); }
html:not(.dark)  .prof-sep { border-color:#f1f5f9; }

/* ── Avatar inner bg ── */
html.dark        .prof-avatar-bg { background:#0d1117; }
html:not(.dark)  .prof-avatar-bg { background:#f1f5f9; }

/* ── Charm pill bg ── */
html.dark        .prof-charm-bg { background:#0d1117; border-color:rgba(255,255,255,.1); }
html:not(.dark)  .prof-charm-bg { background:#f1f5f9; border-color:#e2e8f0; }

/* ── Blobs ── */
html.dark        .blob-1 { background:rgba(16,185,129,.10); }
html:not(.dark)  .blob-1 { background:rgba(16,185,129,.06); }
html.dark        .blob-2 { background:rgba(15,23,42,.30); }
html:not(.dark)  .blob-2 { background:rgba(203,213,225,.15); }

/* ── Rarity colours ── */
.r-common { color:#94a3b8; }
html.dark        .r-uncommon  { color:#4ade80; }
html:not(.dark)  .r-uncommon  { color:#16a34a; }
html.dark        .r-rare      { color:#60a5fa; }
html:not(.dark)  .r-rare      { color:#2563eb; }
html.dark        .r-epic      { color:#a78bfa; }
html:not(.dark)  .r-epic      { color:#7c3aed; }
html.dark        .r-legendary { color:#fbbf24; }
html:not(.dark)  .r-legendary { color:#d97706; }

/* ── Title tag ── */
.ttag { display:inline-block; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; border:1px solid; }

/* ── Stat bar ── */
.sbar { height:4px; border-radius:99px; overflow:hidden; }
html.dark        .sbar { background:rgba(255,255,255,.05); }
html:not(.dark)  .sbar { background:rgba(0,0,0,.07); }
.sfil { height:100%; background:linear-gradient(90deg,#10b981,#34d399); border-radius:99px; transition:width 1.4s cubic-bezier(.4,0,.2,1); }

.bpulse { animation:bp 2.5s ease-in-out infinite; }
@keyframes bp { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.2)} 50%{box-shadow:0 0 0 8px rgba(16,185,129,0)} }
</style>

<div class="pf min-h-screen {{ $themeClass }} prof-text-main">

    {{-- Ambient blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="blob-1 absolute top-0 left-1/3 w-[600px] h-[500px] rounded-full blur-[140px]"></div>
        <div class="blob-2 absolute bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 py-12">

        {{-- Back --}}
        <div class="fu mb-6" style="animation-delay:0ms">
            <a href="{{ route('friends') }}" class="inline-flex items-center gap-2 mn text-xs uppercase tracking-[.15em] prof-text-muted hover:text-emerald-500 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Friends
            </a>
        </div>

        {{-- ═══ HERO ═══ --}}
        <div class="fu prof-hero mb-8 rounded-3xl p-8 relative overflow-hidden" style="animation-delay:50ms">
            <div class="absolute inset-0 opacity-[0.025]" style="background-image:repeating-linear-gradient(45deg,rgba(0,0,0,.3) 0,rgba(0,0,0,.3) 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-8">

                {{-- Avatar with frame --}}
                <div class="relative shrink-0">
                    <div class="av-ring rounded-[22px]" style="{{ $frameStyle ?: '' }}; width:112px; height:112px;">
                        <div class="prof-avatar-bg rounded-[19px] flex items-center justify-center" style="width:100%;height:100%;">
                            <span class="dsp text-5xl prof-text-main">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    @if($eCharms->isNotEmpty())
                    <div class="absolute -bottom-2 -right-2 flex gap-1">
                        @foreach($eCharms as $ch)
                            @php $cm = json_decode($ch->meta, true); @endphp
                            <div class="prof-charm-bg w-8 h-8 rounded-lg border flex items-center justify-center text-sm">{{ $cm['emoji'] ?? '?' }}</div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Name, title, stats --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <h1 class="dsp text-5xl prof-text-main">{{ $user->name }}</h1>
                        @if($eTitle && $titleMeta)
                        <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span>
                        @endif
                    </div>
                    <p class="mn text-xs prof-text-muted mb-5">Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}</p>
                    <div class="flex flex-wrap gap-7">
                        @foreach([
                            ['Win Rate', $winRate.'%',                  'text-emerald-600 dark:text-white'],
                            ['Bets',     $totalBets,                    'prof-text-main'],
                            ['Won',      $wonBets,                      'text-emerald-600 dark:text-emerald-400'],
                            ['Earned',   number_format($totalPayout,0), 'text-amber-600 dark:text-amber-400'],
                        ] as [$l, $v, $c])
                        <div>
                            <p class="mn text-xs prof-text-muted mb-0.5">{{ $l }}</p>
                            <p class="dsp text-2xl {{ $c }}">{{ $v }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Badge --}}
                <div class="shrink-0 text-right">
                    <div class="prof-inner inline-flex items-center gap-2 px-4 py-2.5 rounded-xl">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 bpulse"></div>
                        <span class="mn text-xs prof-text-muted">Public Profile</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ MAIN CONTENT ═══ --}}
        <div class="fu grid grid-cols-1 lg:grid-cols-3 gap-5" style="animation-delay:100ms">

            {{-- Left: Performance + Recent Activity --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Performance --}}
                <div class="prof-card rounded-2xl p-6">
                    <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-5">Performance</p>
                    <div class="grid grid-cols-3 gap-3 mb-5">
                        @foreach([
                            ['Won',   $wonBets,              'text-emerald-600 dark:text-emerald-400'],
                            ['Lost',  $totalBets - $wonBets, 'text-red-500 dark:text-red-400'],
                            ['Total', $totalBets,            'prof-text-main'],
                        ] as [$l, $v, $c])
                        <div class="prof-inner rounded-xl p-4 text-center">
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

                {{-- Recent Activity --}}
                <div class="prof-card rounded-2xl p-6">
                    <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Recent Activity</p>
                    @forelse($recentBets as $b)
                    <div class="flex items-center justify-between py-3 border-b prof-sep last:border-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-2 h-2 rounded-full shrink-0 {{ $b->status==='won'?'bg-emerald-500':($b->status==='lost'?'bg-red-500':'bg-slate-400') }}"></div>
                            <span class="text-sm font-bold prof-text-main truncate">{{ $b->name }}</span>
                        </div>
                        <span class="mn text-xs font-bold ml-4 shrink-0 {{ $b->status==='won'?'text-emerald-600 dark:text-emerald-400':'text-red-500 dark:text-red-400' }}">
                            {{ $b->status==='won' ? '+'.number_format($b->payout - $b->bet_amount, 0) : '-'.number_format($b->bet_amount, 0) }}
                        </span>
                    </div>
                    @empty
                    <p class="prof-text-muted text-sm text-center py-6">No bets yet</p>
                    @endforelse
                </div>
            </div>

            {{-- Right: Equipped + Showcase + Earned --}}
            <div class="space-y-4">

                {{-- Equipped --}}
                <div class="prof-card rounded-2xl p-5">
                    <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Equipped</p>
                    <div class="space-y-2">
                        @foreach([
                            'frame'   => 'Frame',
                            'title'   => 'Title',
                            'theme'   => 'Theme',
                            'charm_1' => 'Charm 1',
                            'charm_2' => 'Charm 2',
                            'charm_3' => 'Charm 3',
                        ] as $slot => $label)
                        @php $eq = $equippedRows->get($slot); @endphp
                        <div class="prof-inline-bg flex items-center justify-between py-2 px-3 rounded-xl border">
                            <span class="mn text-xs prof-text-muted">{{ $label }}</span>
                            <span class="text-xs font-bold {{ $eq ? 'text-emerald-600 dark:text-emerald-400' : 'prof-text-sub' }}">
                                @if($eq)
                                    @php $slotMeta = json_decode($eq->meta, true); @endphp
                                    @if($slot === 'title' && $titleMeta)
                                        <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eq->name }}</span>
                                    @elseif(in_array($slot, ['charm_1','charm_2','charm_3']) && isset($slotMeta['emoji']))
                                        {{ $slotMeta['emoji'] }} {{ $eq->name }}
                                    @else
                                        {{ $eq->name }}
                                    @endif
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Showcase --}}
                @if($eFrame || $eTitle || $eCharms->isNotEmpty())
                <div class="prof-card rounded-2xl p-5">
                    <p class="mn text-xs uppercase tracking-[.2em] prof-text-muted mb-4">Showcase</p>
                    <div class="prof-inner rounded-2xl p-6 flex flex-col items-center gap-4">
                        <div class="av-ring rounded-[18px]" style="{{ $frameStyle ?: '' }}; width:72px; height:72px;">
                            <div class="prof-avatar-bg rounded-[15px] flex items-center justify-center" style="width:100%;height:100%;">
                                <span class="dsp text-3xl prof-text-main">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="dsp text-xl prof-text-main">{{ $user->name }}</p>
                            @if($eTitle && $titleMeta)
                            <div class="mt-1"><span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span></div>
                            @endif
                        </div>
                        @if($eCharms->isNotEmpty())
                        <div class="flex gap-2">
                            @foreach($eCharms as $ch)
                            @php $cm = json_decode($ch->meta, true); @endphp
                            <div class="prof-charm-bg w-9 h-9 rounded-xl border flex items-center justify-center text-lg">{{ $cm['emoji'] ?? '?' }}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- All-time earned --}}
                <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
                    <p class="mn text-xs uppercase tracking-[.2em] text-amber-600/60 mb-2">All-time Earned</p>
                    <p class="dsp text-5xl text-amber-600 dark:text-amber-400">{{ number_format($totalPayout, 0) }}</p>
                    <p class="mn text-xs prof-text-muted mt-1">coins won</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
window.addEventListener('load', () => {
    document.querySelectorAll('.sfil').forEach(b => {
        const w = b.style.width;
        b.style.width = '0';
        setTimeout(() => b.style.width = w, 400);
    });
});
</script>
</x-app-layout>