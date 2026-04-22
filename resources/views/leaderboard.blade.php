<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/4 w-[600px] h-[500px] bg-emerald-900/10 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-1/3 w-[400px] h-[400px] bg-emerald-950/15 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-14">

        {{-- Header --}}
        <div class="mb-10 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-2">Rankings</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Leaderboard</h1>
            <p class="text-sm text-slate-500 mt-1">Top players across all wagers</p>
        </div>

        {{-- Tab switcher --}}
        <div class="fade-up mb-8" style="animation-delay:40ms" x-data="{ tab: 'winnings' }">

            <div class="inline-flex gap-1 p-1 rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] shadow-sm mb-8">
                @foreach([
                    ['winnings', 'Coins Won'],
                    ['winrate',  'Win Rate'],
                    ['wagers',   'Most Wagers'],
                    ['balance',  'Richest'],
                ] as [$key, $label])
                <button @click="tab = '{{ $key }}'" type="button"
                    :class="tab === '{{ $key }}'
                        ? 'bg-emerald-600 text-white shadow-sm'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Coins Won --}}
            <div x-show="tab === 'winnings'" x-cloak>
                @include('leaderboard-table', [
                    'rows'       => $byWinnings,
                    'valueLabel' => 'Coins Won',
                    'valueKey'   => 'total_won',
                    'format'     => 'coins',
                    'icon'       => 'trophy',
                ])
            </div>

            {{-- Win Rate --}}
            <div x-show="tab === 'winrate'" x-cloak>
                @if($byWinRate->isEmpty())
                    <div class="rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-12 text-center shadow-sm">
                        <p class="text-slate-500 text-sm">Not enough data yet — players need at least 3 bets to qualify.</p>
                    </div>
                @else
                    @include('leaderboard-table', [
                        'rows'       => $byWinRate,
                        'valueLabel' => 'Win Rate',
                        'valueKey'   => 'win_rate',
                        'format'     => 'percent',
                        'icon'       => 'target',
                    ])
                @endif
            </div>

            {{-- Most Wagers --}}
            <div x-show="tab === 'wagers'" x-cloak>
                @include('leaderboard-table', [
                    'rows'       => $byWagers,
                    'valueLabel' => 'Wagers',
                    'valueKey'   => 'total_wagers',
                    'format'     => 'number',
                    'icon'       => 'fire',
                ])
            </div>

            {{-- Richest --}}
            <div x-show="tab === 'balance'" x-cloak>
                @include('leaderboard-table', [
                    'rows'       => $byBalance,
                    'valueLabel' => 'Balance',
                    'valueKey'   => 'balance',
                    'format'     => 'coins',
                    'icon'       => 'coin',
                ])
            </div>

        </div>
    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>
