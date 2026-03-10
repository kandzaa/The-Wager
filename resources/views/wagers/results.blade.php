<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap');

.results-page { font-family: 'Syne', sans-serif; }
.mono { font-family: 'DM Mono', monospace; }

.fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.5s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

.winner-glow { box-shadow: 0 0 40px rgba(16,185,129,0.15), 0 0 80px rgba(16,185,129,0.07); }
.pot-number { background: linear-gradient(135deg, #34d399, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

.bar-fill { transition: width 1.2s cubic-bezier(0.4,0,0.2,1); }
.bar-track { background: rgba(255,255,255,0.05); }

.player-row { transition: all 0.2s ease; }
.player-row:hover { background: rgba(255,255,255,0.03); }

.rank-1 { background: linear-gradient(135deg, rgba(251,191,36,0.15), rgba(251,191,36,0.05)); border-color: rgba(251,191,36,0.3) !important; }
.rank-2 { background: linear-gradient(135deg, rgba(148,163,184,0.1), rgba(148,163,184,0.03)); border-color: rgba(148,163,184,0.2) !important; }
.rank-3 { background: linear-gradient(135deg, rgba(180,83,9,0.1), rgba(180,83,9,0.03)); border-color: rgba(180,83,9,0.2) !important; }

.stat-card { backdrop-filter: blur(10px); }
.choice-bar { height: 6px; border-radius: 99px; background: linear-gradient(90deg, #10b981, #34d399); }
.choice-bar-lose { background: linear-gradient(90deg, #475569, #64748b); }

@keyframes countUp { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
.count-up { animation: countUp 0.6s cubic-bezier(0.34,1.56,0.64,1) forwards; }
</style>

<div class="results-page min-h-screen bg-[#080b0f] text-white relative overflow-hidden">

    {{-- Background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[800px] h-[600px] bg-emerald-900/10 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
        <div class="absolute top-1/2 left-0 w-[300px] h-[300px] bg-slate-800/30 rounded-full blur-[80px]"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-14">

        {{-- Back --}}
        <div class="mb-10 fade-up">
            <a href="{{ route('wagers.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.2em] font-bold text-slate-500 hover:text-emerald-400 transition-colors duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Back to Lobby
            </a>
        </div>

        {{-- Hero Header --}}
        <div class="fade-up mb-8" style="animation-delay:60ms">
            <div class="flex items-start justify-between gap-6 mb-6">
                <div>
                    <p class="mono text-xs text-emerald-500/70 tracking-[0.2em] uppercase mb-2">Final Results</p>
                    <h1 class="text-4xl font-extrabold tracking-tight text-white leading-tight">{{ $wager->name }}</h1>
                    @if($wager->description)
                        <p class="text-slate-400 mt-2 text-sm max-w-xl">{{ $wager->description }}</p>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <p class="mono text-xs text-slate-500 tracking-widest uppercase mb-1">Ended</p>
                    <p class="mono text-xs text-slate-400">{{ $wager->updated_at->format('M j, Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Winner Banner --}}
        <div class="fade-up winner-glow rounded-2xl border border-emerald-500/20 bg-gradient-to-br from-emerald-950/60 to-emerald-900/20 p-8 mb-6" style="animation-delay:100ms">
            <div class="flex items-center justify-between gap-6 flex-wrap">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-emerald-500/80 font-bold mono mb-1">Winning Choice</p>
                        <p class="text-3xl font-extrabold text-emerald-300 tracking-tight">{{ $winningChoice->label }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-8">
                    <div class="text-center">
                        <p class="text-xs uppercase tracking-widest text-slate-500 mono mb-1">Total Pot</p>
                        <p class="text-4xl font-extrabold pot-number mono count-up">{{ number_format($wager->pot, 0) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs uppercase tracking-widest text-slate-500 mono mb-1">Bet on Winner</p>
                        <p class="text-4xl font-extrabold text-white mono count-up">{{ number_format($winningChoice->total_bet, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="fade-up grid grid-cols-2 md:grid-cols-4 gap-3 mb-6" style="animation-delay:140ms">
            @php
                $winners = $results->where('status','won')->count();
                $losers = $results->where('status','lost')->count();
                $totalBet = $results->sum('total_bet');
                $totalPayout = $results->sum('payout');
            @endphp
            @foreach([
                ['label' => 'Players', 'value' => $results->count(), 'sub' => 'participated', 'color' => 'text-white'],
                ['label' => 'Winners', 'value' => $winners, 'sub' => 'correct pick', 'color' => 'text-emerald-400'],
                ['label' => 'Losers', 'value' => $losers, 'sub' => 'wrong pick', 'color' => 'text-red-400'],
                ['label' => 'Win Rate', 'value' => $results->count() > 0 ? round(($winners / $results->count()) * 100) . '%' : '0%', 'sub' => 'success rate', 'color' => 'text-amber-400'],
            ] as $stat)
            <div class="stat-card rounded-xl border border-white/[0.07] bg-white/[0.02] p-5">
                <p class="mono text-xs text-slate-500 uppercase tracking-widest mb-2">{{ $stat['label'] }}</p>
                <p class="text-3xl font-extrabold {{ $stat['color'] }} mono">{{ $stat['value'] }}</p>
                <p class="text-xs text-slate-600 mt-1">{{ $stat['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Choice Distribution --}}
        <div class="fade-up rounded-2xl border border-white/[0.07] bg-white/[0.02] p-6 mb-6" style="animation-delay:160ms">
            <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500 mb-5">Bet Distribution</p>
            <div class="space-y-4">
                @foreach($wager->choices as $choice)
                @php
                    $pct = $wager->pot > 0 ? ($choice->total_bet / $wager->pot) * 100 : 0;
                    $isWinner = $choice->id === $winningChoice->id;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            @if($isWinner)
                                <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                            @else
                                <div class="w-5 h-5 rounded-full border border-slate-700 shrink-0"></div>
                            @endif
                            <span class="font-bold text-sm {{ $isWinner ? 'text-emerald-300' : 'text-slate-400' }}">{{ $choice->label }}</span>
                            @if($isWinner)
                                <span class="mono text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">Winner</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="mono text-sm font-bold {{ $isWinner ? 'text-emerald-400' : 'text-slate-400' }}">{{ number_format($choice->total_bet, 0) }}</span>
                            <span class="mono text-xs text-slate-600 ml-2">{{ number_format($pct, 1) }}%</span>
                        </div>
                    </div>
                    <div class="w-full bar-track rounded-full h-1.5">
                        <div class="{{ $isWinner ? 'choice-bar' : 'choice-bar-lose' }} bar-fill rounded-full h-1.5" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Players Results --}}
        <div class="fade-up rounded-2xl border border-white/[0.07] bg-white/[0.02] overflow-hidden mb-6" style="animation-delay:200ms">
            <div class="px-6 py-4 border-b border-white/[0.05] flex items-center justify-between">
                <p class="mono text-xs uppercase tracking-[0.2em] text-slate-500">Player Results</p>
                <p class="mono text-xs text-slate-600">{{ $results->count() }} players</p>
            </div>

            <div class="divide-y divide-white/[0.04]">
                @forelse($results as $i => $result)
                @php $rank = $i + 1; @endphp
                <div class="player-row {{ $rank === 1 ? 'rank-1' : ($rank === 2 ? 'rank-2' : ($rank === 3 ? 'rank-3' : '')) }} border border-transparent">

                    {{-- Main row --}}
                    <div class="px-6 py-4 flex items-center gap-4 cursor-pointer" onclick="togglePlayer({{ $i }})">

                        {{-- Rank --}}
                        <div class="w-8 text-center shrink-0">
                            @if($rank === 1)
                                <span class="text-amber-400 text-lg">🥇</span>
                            @elseif($rank === 2)
                                <span class="text-slate-400 text-lg">🥈</span>
                            @elseif($rank === 3)
                                <span class="text-amber-700 text-lg">🥉</span>
                            @else
                                <span class="mono text-xs text-slate-600">#{{ $rank }}</span>
                            @endif
                        </div>

                        {{-- Avatar + Name --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-xl {{ $result['status'] === 'won' ? 'bg-emerald-500/20 border border-emerald-500/30' : 'bg-slate-800 border border-slate-700' }} flex items-center justify-center text-sm font-black shrink-0 {{ $result['status'] === 'won' ? 'text-emerald-400' : 'text-slate-500' }}">
                                {{ strtoupper(substr($result['user']->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-white truncate">{{ $result['user']->name }}</p>
                                @if($wager->creator_id === $result['user']->id)
                                    <p class="mono text-xs text-slate-600">Creator</p>
                                @endif
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="hidden md:flex items-center gap-8 shrink-0">
                            <div class="text-center w-20">
                                <p class="mono text-xs text-slate-600 mb-0.5">Bet</p>
                                <p class="mono text-sm font-bold text-slate-300">{{ number_format($result['total_bet'], 0) }}</p>
                            </div>
                            <div class="text-center w-20">
                                <p class="mono text-xs text-slate-600 mb-0.5">Payout</p>
                                <p class="mono text-sm font-bold {{ $result['payout'] > 0 ? 'text-emerald-400' : 'text-slate-500' }}">{{ number_format($result['payout'], 0) }}</p>
                            </div>
                            <div class="text-center w-24">
                                <p class="mono text-xs text-slate-600 mb-0.5">Profit</p>
                                <p class="mono text-sm font-black {{ $result['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $result['profit'] >= 0 ? '+' : '' }}{{ number_format($result['profit'], 0) }}</p>
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <div class="shrink-0 flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full mono text-xs font-bold {{ $result['status'] === 'won' ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border border-red-500/20 text-red-400' }}">
                                {{ $result['status'] === 'won' ? 'Won' : 'Lost' }}
                            </span>
                            <svg id="chevron-{{ $i }}" class="w-4 h-4 text-slate-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Expandable bet details --}}
                    <div id="player-{{ $i }}" class="hidden px-6 pb-4">
                        <div class="ml-11 space-y-2">
                            <p class="mono text-xs text-slate-600 uppercase tracking-widest mb-3">Individual Bets</p>
                            @foreach($result['bets'] as $bet)
                            <div class="flex items-center justify-between py-2.5 px-4 rounded-xl bg-black/20 border border-white/[0.04]">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $bet['is_winner'] ? 'bg-emerald-500' : 'bg-slate-700' }} shrink-0"></div>
                                    <span class="text-sm font-semibold {{ $bet['is_winner'] ? 'text-emerald-300' : 'text-slate-400' }}">{{ $bet['choice'] }}</span>
                                    @if($bet['is_winner'])
                                        <span class="mono text-xs text-emerald-600">winner</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-6 mono text-xs">
                                    <span class="text-slate-500">Bet: <span class="text-slate-300 font-bold">{{ number_format($bet['amount'], 0) }}</span></span>
                                    @if($bet['is_winner'])
                                        <span class="text-slate-500">Payout: <span class="text-emerald-400 font-bold">{{ number_format($bet['payout'], 0) }}</span></span>
                                        <span class="font-black {{ $bet['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $bet['profit'] >= 0 ? '+' : '' }}{{ number_format($bet['profit'], 0) }}</span>
                                    @else
                                        <span class="font-black text-red-400">-{{ number_format($bet['amount'], 0) }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-slate-600 text-sm">No participants found</p>
                </div>
                @endforelse
            </div>

            {{-- Totals footer --}}
            <div class="px-6 py-4 border-t border-white/[0.07] bg-black/20">
                <div class="flex items-center justify-between ml-11">
                    <p class="mono text-xs text-slate-500 uppercase tracking-widest">Totals</p>
                    <div class="flex items-center gap-8">
                        <div class="hidden md:block text-center w-20">
                            <p class="mono text-sm font-black text-white">{{ number_format($results->sum('total_bet'), 0) }}</p>
                        </div>
                        <div class="hidden md:block text-center w-20">
                            <p class="mono text-sm font-black text-emerald-400">{{ number_format($results->sum('payout'), 0) }}</p>
                        </div>
                        <div class="hidden md:block text-center w-24">
                            <p class="mono text-sm font-black {{ $results->sum('profit') >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $results->sum('profit') >= 0 ? '+' : '' }}{{ number_format($results->sum('profit'), 0) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="mono text-xs text-slate-500">{{ $results->where('status','won')->count() }}/{{ $results->count() }} won</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="fade-up flex gap-3" style="animation-delay:240ms">
            <a href="{{ route('history') }}"
                class="flex-1 py-3 text-sm font-bold text-center rounded-xl
                       bg-white/[0.03] hover:bg-white/[0.06]
                       border border-white/[0.08]
                       text-slate-400 hover:text-white
                       transition-all duration-200 mono tracking-wide">
                View History
            </a>
            @if(auth()->id() === $wager->creator_id)
            <form action="{{ route('wagers.destroy', $wager) }}" method="POST"
                  onsubmit="return confirm('Delete this wager? This cannot be undone.')" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full py-3 text-sm font-bold rounded-xl
                           bg-red-950/30 hover:bg-red-900/40
                           border border-red-500/20 hover:border-red-500/40
                           text-red-400
                           transition-all duration-200 active:scale-95 mono tracking-wide">
                    Delete Wager
                </button>
            </form>
            @endif
        </div>

    </div>
</div>

<script>
function togglePlayer(index) {
    const row = document.getElementById('player-' + index);
    const chevron = document.getElementById('chevron-' + index);
    row.classList.toggle('hidden');
    chevron.style.transform = row.classList.contains('hidden') ? '' : 'rotate(180deg)';
}

// Animate bars on load
document.addEventListener('DOMContentLoaded', function() {
    const bars = document.querySelectorAll('.bar-fill');
    bars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => { bar.style.width = width; }, 300);
    });
});
</script>
</x-app-layout>