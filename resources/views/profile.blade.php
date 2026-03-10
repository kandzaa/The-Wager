<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cabinet+Grotesk:wght@400;700;800;900&family=DM+Mono:wght@400;500&display=swap');

:root { --accent:#10b981; }
.pf  { font-family:'Cabinet Grotesk',sans-serif; }
.dsp { font-family:'Bebas Neue',sans-serif; letter-spacing:0.05em; }
.mn  { font-family:'DM Mono',monospace; }

.fu { opacity:0; transform:translateY(14px); animation:fu .4s ease forwards; }
@keyframes fu { to { opacity:1; transform:translateY(0); } }

.tb { padding:12px 20px; font-size:13px; font-weight:800; color:#475569; transition:color .2s; position:relative; }
.tb:hover { color:#fff; }
.tb.on { color:#fff; }
.tb.on::after { content:''; position:absolute; bottom:-1px; left:0; right:0; height:2px; background:#10b981; border-radius:99px; }
.tc { display:none; }
.tc.on { display:block; animation:fu .25s ease; }

.av-ring { display:block; padding:3px; }
.f-none    .av-ring { background:rgba(255,255,255,.08); }
.f-gold    .av-ring { background:linear-gradient(135deg,#f59e0b,#d97706,#fbbf24); }
.f-crimson .av-ring { background:linear-gradient(135deg,#ef4444,#b91c1c); }
.f-void    .av-ring { background:linear-gradient(135deg,#7c3aed,#4c1d95); }
.f-aurora  .av-ring { background:linear-gradient(135deg,#10b981,#3b82f6,#8b5cf6,#ef4444); }

.bg-default  { background:#080b0f; }
.bg-midnight { background:linear-gradient(160deg,#0f0c29 0%,#1a1640 100%); }
.bg-crimson  { background:linear-gradient(160deg,#1a0505 0%,#2d0a0a 100%); }
.bg-void     { background:linear-gradient(160deg,#050510 0%,#0d0520 100%); }

.r-common    { color:#94a3b8; }
.r-uncommon  { color:#4ade80; }
.r-rare      { color:#60a5fa; }
.r-epic      { color:#a78bfa; }
.r-legendary { color:#fbbf24; }

.ttag { display:inline-block; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; border:1px solid; }

.ccard { transition:transform .2s,box-shadow .2s; }
.ccard:hover { transform:translateY(-2px); }
.ccard.is-equipped { box-shadow:0 0 0 2px #10b981,0 0 18px rgba(16,185,129,.2); }

.sbar { height:4px; background:rgba(255,255,255,.05); border-radius:99px; overflow:hidden; }
.sfil { height:100%; background:linear-gradient(90deg,#10b981,#34d399); border-radius:99px; transition:width 1.4s cubic-bezier(.4,0,.2,1); }

.bpulse { animation:bp 2.5s ease-in-out infinite; }
@keyframes bp { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.2)} 50%{box-shadow:0 0 0 8px rgba(16,185,129,0)} }

.toast { position:fixed; bottom:24px; right:24px; z-index:9999; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; font-family:'DM Mono',monospace; transform:translateY(60px); opacity:0; transition:all .3s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
.toast.show { transform:translateY(0); opacity:1; }
.toast.ok  { background:#0d2818; border:1px solid rgba(16,185,129,.4); color:#34d399; }
.toast.err { background:#2d0a0a; border:1px solid rgba(239,68,68,.4);  color:#f87171; }

.eq-btn { width:100%; padding:8px; border-radius:10px; font-size:12px; font-weight:700; transition:all .2s; border:1px solid; }
.eq-off { background:rgba(16,185,129,.07); border-color:rgba(16,185,129,.2); color:#10b981; }
.eq-off:hover { background:rgba(16,185,129,.15); }
.eq-on  { background:rgba(16,185,129,.18); border-color:rgba(16,185,129,.5); color:#34d399; }
</style>

@php
    $user = Auth::user();

    $totalBets   = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->count();
    $wonBets     = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->where('b.status','won')->count();
    $totalPayout = DB::table('wager_bets as b')->join('wager_players as p','b.wager_player_id','=','p.id')->where('p.user_id',$user->id)->sum('b.payout');
    $winRate     = $totalBets > 0 ? round(($wonBets/$totalBets)*100) : 0;

    $ownedIds = DB::table('user_cosmetics')->where('user_id',$user->id)->pluck('cosmetic_id')->toArray();

    // LEFT JOIN so orphaned cosmetic references never hard-fail and poison the connection
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
    $frameStyle = ($frameMeta && isset($frameMeta['gradient'])) ? "background:{$frameMeta['gradient']}" : 'background:rgba(255,255,255,.08)';
    $themeClass = $themeMeta['bg_class'] ?? 'bg-default';

    $shop = \App\Models\Cosmetic::all()->groupBy('type');

    $recentBets = DB::table('wager_bets as b')
        ->join('wager_players as p','b.wager_player_id','=','p.id')
        ->join('wagers as w','b.wager_id','=','w.id')
        ->where('p.user_id',$user->id)
        ->select('w.name','b.bet_amount','b.payout','b.status')
        ->orderByDesc('b.updated_at')->limit(5)->get();
@endphp

<div class="pf min-h-screen {{ $themeClass }} text-white">

    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/3 w-[600px] h-[500px] bg-emerald-900/10 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-slate-900/30 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12">

        {{-- ═══ HERO ═══ --}}
        <div class="fu mb-8 rounded-3xl border border-white/[0.08] bg-white/[0.02] p-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.025]" style="background-image:repeating-linear-gradient(45deg,rgba(255,255,255,.5) 0,rgba(255,255,255,.5) 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-8">

                <div class="relative shrink-0">
                    <div class="av-ring rounded-[22px]" style="{{ $frameStyle }}; width:112px; height:112px;">
                        <div class="rounded-[19px] bg-[#0d1117] flex items-center justify-center" style="width:100%;height:100%;">
                            <span class="dsp text-5xl text-white">{{ strtoupper(substr($user->name,0,1)) }}</span>
                        </div>
                    </div>
                    @if($eCharms->isNotEmpty())
                    <div class="absolute -bottom-2 -right-2 flex gap-1" id="hero-charms">
                        @foreach($eCharms as $ch)
                        @php $cm=json_decode($ch->meta,true); @endphp
                        <div class="w-8 h-8 rounded-lg bg-[#0d1117] border border-white/10 flex items-center justify-center text-sm">{{ $cm['emoji']??'?' }}</div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <h1 class="dsp text-5xl">{{ $user->name }}</h1>
                        <div id="hero-title">
                            @if($eTitle && $titleMeta)
                            <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="mn text-xs text-slate-600 mb-5">Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}</p>
                    <div class="flex flex-wrap gap-7">
                        @foreach([['Win Rate',$winRate.'%','text-white'],['Bets',$totalBets,'text-white'],['Won',$wonBets,'text-emerald-400'],['Earned',number_format($totalPayout,0),'text-amber-400']] as [$l,$v,$c])
                        <div><p class="mn text-xs text-slate-600 mb-0.5">{{ $l }}</p><p class="dsp text-2xl {{ $c }}">{{ $v }}</p></div>
                        @endforeach
                    </div>
                </div>

                <div class="shrink-0 text-right">
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 bpulse">
                        <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-6 h-6">
                        <span class="dsp text-2xl text-amber-400" id="bal">{{ number_format($user->balance,0) }}</span>
                    </div>
                    <p class="mn text-xs text-slate-600 mt-1">coins</p>
                </div>
            </div>
        </div>

        {{-- ═══ TABS ═══ --}}
        <div class="fu border-b border-white/[0.07] mb-6 flex" style="animation-delay:50ms">
            @foreach(['stats'=>'Stats','shop'=>'🛍 Shop','customize'=>'🎨 Customize','settings'=>'Settings'] as $t=>$l)
            <button onclick="switchTab('{{ $t }}')" class="tb {{ $t==='stats'?'on':'' }}" id="tb-{{ $t }}">{{ $l }}</button>
            @endforeach
        </div>

        {{-- ═══ STATS ═══ --}}
        <div class="tc on" id="tc-stats">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 space-y-5">
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                        <p class="mn text-xs uppercase tracking-[.2em] text-slate-500 mb-5">Performance</p>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            @foreach([['Won',$wonBets,'text-emerald-400'],['Lost',$totalBets-$wonBets,'text-red-400'],['Total',$totalBets,'text-white']] as [$l,$v,$c])
                            <div class="rounded-xl bg-white/[0.03] border border-white/[0.05] p-4 text-center">
                                <p class="dsp text-4xl {{ $c }}">{{ $v }}</p>
                                <p class="mn text-xs text-slate-500 mt-1">{{ $l }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mb-1.5">
                            <span class="mn text-xs text-slate-500">Win Rate</span>
                            <span class="mn text-xs text-emerald-400">{{ $winRate }}%</span>
                        </div>
                        <div class="sbar"><div class="sfil" style="width:{{ $winRate }}%"></div></div>
                    </div>

                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                        <p class="mn text-xs uppercase tracking-[.2em] text-slate-500 mb-4">Recent Activity</p>
                        @forelse($recentBets as $b)
                        <div class="flex items-center justify-between py-3 border-b border-white/[0.04] last:border-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-2 h-2 rounded-full shrink-0 {{ $b->status==='won'?'bg-emerald-500':($b->status==='lost'?'bg-red-500':'bg-slate-600') }}"></div>
                                <span class="text-sm font-bold text-slate-300 truncate">{{ $b->name }}</span>
                            </div>
                            <span class="mn text-xs font-bold ml-4 shrink-0 {{ $b->status==='won'?'text-emerald-400':'text-red-400' }}">
                                {{ $b->status==='won' ? '+'.number_format($b->payout-$b->bet_amount,0) : '-'.number_format($b->bet_amount,0) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-slate-600 text-sm text-center py-6">No bets yet</p>
                        @endforelse
                        <a href="{{ route('history') }}" class="block text-center mn text-xs text-emerald-500 hover:text-emerald-400 mt-4 transition-colors">View full history →</a>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mn text-xs uppercase tracking-[.2em] text-slate-500 mb-4">Equipped</p>
                        <div class="space-y-2">
                            @foreach(['frame'=>'Frame','title'=>'Title','theme'=>'Theme','charm_1'=>'Charm 1','charm_2'=>'Charm 2','charm_3'=>'Charm 3'] as $slot=>$label)
                            @php $eq=$equippedRows->get($slot); @endphp
                            <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-white/[0.03] border border-white/[0.04]">
                                <span class="mn text-xs text-slate-600">{{ $label }}</span>
                                <span class="text-xs font-bold {{ $eq?'text-emerald-400':'text-slate-700' }}" data-equipped-slot="{{ $slot }}">{{ $eq?$eq->name:'—' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
                        <p class="mn text-xs uppercase tracking-[.2em] text-amber-600/60 mb-2">All-time Earned</p>
                        <p class="dsp text-5xl text-amber-400">{{ number_format($totalPayout,0) }}</p>
                        <p class="mn text-xs text-slate-600 mt-1">coins won</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SHOP ═══ --}}
        <div class="tc" id="tc-shop">
            <div class="flex gap-2 mb-6 flex-wrap">
                @foreach(['frame'=>'🖼 Frames','title'=>'👑 Titles','theme'=>'🎨 Themes','charm'=>'✨ Charms'] as $cat=>$lbl)
                <button onclick="shopCat('{{ $cat }}')" id="sc-{{ $cat }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-200 {{ $cat==='frame'?'bg-emerald-500/10 border-emerald-500/30 text-emerald-400':'bg-white/[0.03] border-white/[0.07] text-slate-500 hover:text-white' }}">
                    {{ $lbl }}
                </button>
                @endforeach
            </div>

            @foreach(['frame','title','theme','charm'] as $type)
            <div id="sc-c-{{ $type }}" class="{{ $type!=='frame'?'hidden':'' }}">
                <div class="grid {{ $type==='charm'?'grid-cols-3 md:grid-cols-6':($type==='title'?'grid-cols-1 md:grid-cols-2':'grid-cols-2 md:grid-cols-4') }} gap-4">
                    @foreach($shop->get($type,collect()) as $item)
                    @php
                        $m        = $item->meta ?? [];
                        $owned    = in_array($item->id,$ownedIds);
                        $equipped = collect($equippedRows)->contains('id',$item->id);
                    @endphp
                    <div class="ccard rounded-2xl border {{ $equipped?'border-emerald-500/60 bg-emerald-500/5':($owned?'border-white/20 bg-white/[0.04]':'border-white/[0.07] bg-white/[0.02]') }} p-4 relative {{ $equipped?'is-equipped':'' }}"
                         id="card-{{ $item->id }}" data-ctype="{{ $type }}">

                        @if($equipped)
                        <div class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        @endif

                        @if($type==='frame')
                        <div class="w-14 h-14 mx-auto mb-3 p-[3px]" style="background:{{ $m['gradient']??'#333' }}; border-radius:16px;">
                            <div class="w-full h-full bg-[#0d1117] flex items-center justify-center" style="border-radius:13px;">
                                <span class="dsp text-2xl text-white">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            </div>
                        </div>
                        @elseif($type==='theme')
                        <div class="h-14 w-full rounded-xl mb-3" style="background:{{ $m['gradient']??'#333' }}"></div>
                        @elseif($type==='charm')
                        <div class="text-4xl text-center mb-2">{{ $m['emoji']??'?' }}</div>
                        @elseif($type==='title')
                        <div class="mb-3"><span class="ttag {{ $m['bg']??'bg-slate-500/10 border-slate-500/30' }} {{ $m['color']??'text-slate-300' }}">{{ $item->name }}</span></div>
                        @endif

                        <p class="font-bold text-sm text-white text-center mb-0.5">{{ $item->name }}</p>
                        <p class="mn text-xs text-center r-{{ $item->rarity }} mb-3">{{ ucfirst($item->rarity) }}</p>

                        @if($owned)
                        <button class="eq-btn {{ $equipped?'eq-on':'eq-off' }}"
                                onclick="toggleEquip({{ $item->id }},'{{ $type }}',{{ $equipped?'true':'false' }},this)">
                            {{ $equipped ? '✓ Equipped' : 'Equip' }}
                        </button>
                        @else
                        <button class="w-full py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 transition-all active:scale-95"
                                onclick="buyItem({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }},this)">
                            <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 inline"> {{ number_format($item->price) }}
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- ═══ CUSTOMIZE (owned only) ═══ --}}
        <div class="tc" id="tc-customize">
            @php
                $ownedFrames = $shop->get('frame',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedTitles = $shop->get('title',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedThemes = $shop->get('theme',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
                $ownedCharms = $shop->get('charm',collect())->filter(fn($i)=>in_array($i->id,$ownedIds));
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Live preview --}}
                <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6">
                    <p class="mn text-xs uppercase tracking-[.2em] text-slate-500 mb-4">Preview</p>
                    <div class="rounded-2xl border border-white/[0.08] bg-white/[0.02] p-10 flex flex-col items-center gap-4">
                        <div class="relative">
                            <div class="av-ring rounded-[20px]" id="prv-ring" style="{{ $frameStyle }}; width:80px; height:80px;">
                                <div class="rounded-[17px] bg-[#0d1117] flex items-center justify-center" style="width:100%;height:100%;">
                                    <span class="dsp text-3xl text-white">{{ strtoupper(substr($user->name,0,1)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="dsp text-2xl text-white">{{ $user->name }}</p>
                            <div id="prv-title" class="mt-1 min-h-[24px]">
                                @if($eTitle && $titleMeta)
                                <span class="ttag {{ $titleMeta['bg'] }} {{ $titleMeta['color'] }}">{{ $eTitle->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 min-h-[40px]" id="prv-charms">
                            @foreach($eCharms as $ch)
                            @php $cm=json_decode($ch->meta,true); @endphp
                            <div class="w-9 h-9 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-lg">{{ $cm['emoji']??'?' }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-4">

                    {{-- Frame --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mn text-xs uppercase tracking-[.15em] text-slate-500 mb-3">Frame</p>
                        @if($ownedFrames->isEmpty())
                        <p class="text-xs text-slate-600">None owned — <button onclick="switchTab('shop');shopCat('frame')" class="text-emerald-500 hover:underline">browse shop</button></p>
                        @else
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="equipItem('frame',null,null,this)" class="w-10 h-10 rounded-xl border-2 border-white/10 bg-white/[0.03] hover:border-white/20 transition-all text-slate-600 text-xs font-bold">✕</button>
                            @foreach($ownedFrames as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipItem('frame',{{ $item->id }},'{{ $m['gradient']??'' }}',this)"
                                    class="w-10 h-10 rounded-xl border-2 transition-all {{ $eq?'border-emerald-500 shadow-[0_0_0_1px_#10b981]':'border-transparent hover:border-white/20' }}"
                                    style="background:{{ $m['gradient']??'#333' }}" title="{{ $item->name }}"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Title --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mn text-xs uppercase tracking-[.15em] text-slate-500 mb-3">Title</p>
                        @if($ownedTitles->isEmpty())
                        <p class="text-xs text-slate-600">None owned — <button onclick="switchTab('shop');shopCat('title')" class="text-emerald-500 hover:underline">browse shop</button></p>
                        @else
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="equipTitle(null,null,null)" class="px-3 py-1.5 rounded-lg border border-white/10 text-xs text-slate-500 font-bold hover:border-white/20 transition-all">None</button>
                            @foreach($ownedTitles as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipTitle({{ $item->id }},'{{ $m['bg']??'' }}','{{ $m['color']??'' }}','{{ addslashes($item->name) }}')"
                                    class="ttag {{ $m['bg']??'' }} {{ $m['color']??'' }} cursor-pointer hover:opacity-80 transition-all {{ $eq?'ring-2 ring-emerald-500':'' }}">
                                {{ $item->name }}
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Theme --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mn text-xs uppercase tracking-[.15em] text-slate-500 mb-3">Theme</p>
                        @if($ownedThemes->isEmpty())
                        <p class="text-xs text-slate-600">None owned — <button onclick="switchTab('shop');shopCat('theme')" class="text-emerald-500 hover:underline">browse shop</button></p>
                        @else
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="equipTheme(null,null)" class="px-3 py-1.5 rounded-lg border border-white/10 text-xs text-slate-400 font-bold hover:border-white/20 transition-all">Default</button>
                            @foreach($ownedThemes as $item)
                            @php $m=$item->meta??[]; $eq=collect($equippedRows)->contains('id',$item->id); @endphp
                            <button onclick="equipTheme({{ $item->id }},'{{ $m['bg_class']??'' }}')"
                                    class="px-3 py-1.5 rounded-lg border-2 text-xs font-bold transition-all {{ $eq?'border-emerald-500 text-emerald-400':'border-white/10 text-slate-400 hover:border-white/20' }}"
                                    style="background:{{ $m['gradient']??'#1e293b' }}">{{ $item->name }}</button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Charms --}}
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] p-5">
                        <p class="mn text-xs uppercase tracking-[.15em] text-slate-500 mb-3">Charms <span class="text-slate-700 tracking-normal text-[11px] font-normal">(3 slots)</span></p>
                        @if($ownedCharms->isEmpty())
                        <p class="text-xs text-slate-600">None owned — <button onclick="switchTab('shop');shopCat('charm')" class="text-emerald-500 hover:underline">browse shop</button></p>
                        @else
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            @foreach(['charm_1','charm_2','charm_3'] as $slot)
                            @php $eq=$equippedRows->get($slot); $cm=$eq?json_decode($eq->meta,true):null; @endphp
                            <div id="charm-slot-{{ $slot }}" class="rounded-xl border {{ $eq?'border-emerald-500/40 bg-emerald-500/5':'border-white/[0.07]' }} p-3 text-center">
                                <p class="mn text-[10px] text-slate-600 mb-1">{{ strtoupper(str_replace('_',' ',$slot)) }}</p>
                                <div class="text-2xl mb-1.5 charm-emoji">{{ $cm?$cm['emoji']:'—' }}</div>
                                @if($eq)
                                <button onclick="equipCharm('{{ $slot }}',null)" class="charm-remove mn text-[10px] text-red-400 hover:text-red-300 transition-colors">remove</button>
                                @else
                                <button onclick="equipCharm('{{ $slot }}',null)" class="charm-remove mn text-[10px] text-red-400 hover:text-red-300 transition-colors" style="display:none">remove</button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach($ownedCharms as $item)
                            @php $m=$item->meta??[]; @endphp
                            <button onclick="pickCharm({{ $item->id }},'{{ $m['emoji']??'?' }}')"
                                    class="w-10 h-10 rounded-xl border border-white/[0.07] bg-white/[0.02] hover:border-emerald-500/40 transition-all text-xl flex items-center justify-center"
                                    title="{{ $item->name }}">{{ $m['emoji']??'?' }}</button>
                            @endforeach
                        </div>
                        <p class="mn text-xs text-slate-700 mt-2">Tap to fill next empty slot</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SETTINGS ═══ --}}
        <div class="tc" id="tc-settings">
            <div class="max-w-lg space-y-4">
                @if(session('success'))
                <div class="px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm mn">✓ {{ session('success') }}</div>
                @endif
                @foreach([['Username','name','text',route('profile.change-username'),$user->name],['Email','email','email',route('profile.change-email'),$user->email]] as [$label,$fname,$ftype,$faction,$fval])
                <div class="rounded-2xl border border-white/[0.07] bg-white/[0.02] overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.05]"><p class="mn text-xs uppercase tracking-[.15em] text-slate-500">{{ $label }}</p></div>
                    <div class="px-6 py-5">
                        <form action="{{ $faction }}" method="POST" class="flex gap-3">@csrf
                            <input type="{{ $ftype }}" name="{{ $fname }}" value="{{ old($fname,$fval) }}" class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 transition-all"/>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all active:scale-95">Save</button>
                        </form>
                        @error($fname)<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
                @endforeach
                <div class="rounded-2xl border border-red-500/20 bg-red-950/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-500/10"><p class="mn text-xs uppercase tracking-[.15em] text-red-500/60">Danger Zone</p></div>
                    <div class="px-6 py-5 flex items-center justify-between">
                        <div><p class="text-sm font-bold text-slate-300">Delete Account</p><p class="text-xs text-slate-600 mt-0.5">Permanently removes everything.</p></div>
                        <button class="px-4 py-2 bg-red-950/60 hover:bg-red-900/60 border border-red-500/20 text-red-400 text-sm font-bold rounded-xl transition-all active:scale-95">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="toast" id="toast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Live state
let state = {
    frame:   { id: {{ $eFrame ? $eFrame->id : 'null' }}, gradient: '{{ $frameMeta ? ($frameMeta["gradient"] ?? "") : "" }}' },
    title:   { id: {{ $eTitle ? $eTitle->id : 'null' }}, bg: '{{ $titleMeta ? ($titleMeta["bg"] ?? "") : "" }}', color: '{{ $titleMeta ? ($titleMeta["color"] ?? "") : "" }}', name: '{{ $eTitle ? addslashes($eTitle->name) : "" }}' },
    theme:   { id: {{ $eTheme ? $eTheme->id : 'null' }}, bgClass: '{{ $themeClass }}' },
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
        document.getElementById('sc-'+c).className = `px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-200 ${on?'bg-emerald-500/10 border-emerald-500/30 text-emerald-400':'bg-white/[0.03] border-white/[0.07] text-slate-500 hover:text-white'}`;
        document.getElementById('sc-c-'+c).classList.toggle('hidden',!on);
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

// ── UI updaters ──────────────────────────────────────────────

function applyFrame(gradient) {
    const bg = gradient || 'rgba(255,255,255,.08)';
    // Hero ring
    document.querySelector('.av-ring').style.background = bg;
    // Preview ring
    document.getElementById('prv-ring').style.background = bg;
}

function applyTitle(id, bg, color, name) {
    const heroTitle  = document.getElementById('hero-title');
    const prvTitle   = document.getElementById('prv-title');
    const html = id && name ? `<span class="ttag ${bg} ${color}">${name}</span>` : '';
    if (heroTitle) heroTitle.innerHTML = html;
    prvTitle.innerHTML = html;
}

function applyCharms() {
    const slots = ['charm_1','charm_2','charm_3'];
    const charms = slots.map(s => state[s].emoji).filter(Boolean);

    // Hero charm badges
    const heroBadges = document.getElementById('hero-charms');
    if (heroBadges) {
        heroBadges.innerHTML = charms.map(e =>
            `<div class="w-8 h-8 rounded-lg bg-[#0d1117] border border-white/10 flex items-center justify-center text-sm">${e}</div>`
        ).join('');
        heroBadges.parentElement.style.display = charms.length ? '' : 'none';
    }

    // Preview charms
    document.getElementById('prv-charms').innerHTML = charms.map(e =>
        `<div class="w-9 h-9 rounded-xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-lg">${e}</div>`
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
        el.textContent = map[el.dataset.equippedSlot] ?? '—';
        el.className = `text-xs font-bold ${map[el.dataset.equippedSlot] !== '—' ? 'text-emerald-400' : 'text-slate-700'}`;
    });
}

function markEquippedCard(id, type) {
    // Remove equipped state from all cards of this type
    document.querySelectorAll(`[data-ctype="${type}"]`).forEach(card => {
        card.classList.remove('is-equipped','border-emerald-500/60','bg-emerald-500/5');
        card.classList.add('border-white/[0.07]','bg-white/[0.02]');
        const badge = card.querySelector('.eq-badge'); if (badge) badge.remove();
        const btn = card.querySelector('.eq-btn');
        if (btn) { btn.classList.remove('eq-on'); btn.classList.add('eq-off'); btn.textContent = 'Equip'; }
    });
    if (!id) return;
    const card = document.getElementById('card-'+id);
    if (!card) return;
    card.classList.add('is-equipped','border-emerald-500/60','bg-emerald-500/5');
    card.classList.remove('border-white/[0.07]','bg-white/[0.02]');
    const btn = card.querySelector('.eq-btn');
    if (btn) { btn.classList.add('eq-on'); btn.classList.remove('eq-off'); btn.textContent = '✓ Equipped'; }
}

// ── Buy ──────────────────────────────────────────────────────

async function buyItem(id, name, price, btn) {
    if (!confirm(`Buy "${name}" for 🪙 ${price.toLocaleString()} coins?`)) return;
    btn.disabled = true; btn.textContent = '...';
    const data = await post('/cosmetics/buy', {cosmetic_id:id});
    if (data.success) {
        toast('✓ ' + data.message);
        document.getElementById('bal').textContent = Number(data.balance).toLocaleString();
        // Swap buy button → equip button without reload
        btn.outerHTML = `<button class="eq-btn eq-off" onclick="toggleEquip(${id},'${btn.dataset.type}',false,this)">Equip</button>`;
        location.reload(); // reload to refresh owned state fully
    } else {
        toast(data.message, 'err');
        btn.disabled = false; btn.textContent = `🪙 ${price.toLocaleString()}`;
    }
}

// ── Equip helpers ─────────────────────────────────────────────

async function toggleEquip(id, type, isEquipped, btn) {
    const slotMap = {frame:'frame',title:'title',theme:'theme',charm:'charm_1'};
    const slot = slotMap[type];
    const data = await post('/cosmetics/equip', {slot, cosmetic_id: isEquipped ? null : id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    if (isEquipped) {
        state[slot] = {id:null, gradient:'', emoji:'', name:'', bg:'', color:''};
    }
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
    // Update customize panel button highlights
    document.querySelectorAll('[data-frame-btn]').forEach(b => {
        b.classList.toggle('border-emerald-500', b.dataset.frameBtn == id);
        b.classList.toggle('shadow-[0_0_0_1px_#10b981]', b.dataset.frameBtn == id);
        b.classList.toggle('border-transparent', b.dataset.frameBtn != id);
    });
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

async function equipTheme(id, bgClass) {
    const data = await post('/cosmetics/equip', {slot:'theme', cosmetic_id:id});
    if (!data.success) { toast(data.message,'err'); return; }
    toast('✓ '+data.message);
    state.theme = {id, bgClass};
    // Swap background class on root div
    const root = document.querySelector('.pf.min-h-screen');
    ['bg-default','bg-midnight','bg-crimson','bg-void'].forEach(c => root.classList.remove(c));
    if (bgClass) root.className = root.className + ' ' + bgClass;
    else root.classList.add('bg-default');
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
    // Refresh charm slot display
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
            el.classList.remove('border-white/[0.07]');
            if (removeBtn) removeBtn.style.display='';
        } else {
            el.classList.remove('border-emerald-500/40','bg-emerald-500/5');
            el.classList.add('border-white/[0.07]');
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