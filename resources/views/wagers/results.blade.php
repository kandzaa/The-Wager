<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/3 w-[600px] h-[500px] bg-emerald-900/15 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 py-14">

        {{-- Back --}}
        <div class="mb-8 fade-up">
            <a href="{{ route('wagers.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Lobby
            </a>
        </div>

        {{-- Header --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-6 mb-4 shadow-sm dark:shadow-none" style="animation-delay:60ms">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white mb-1">{{ $wager->name }}</h1>
                    @if($wager->description)
                        <p class="text-sm text-slate-500 mb-2">{{ $wager->description }}</p>
                    @endif
                    <p class="text-xs text-slate-400 dark:text-slate-600">
                        {{ $wager->updated_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <span class="shrink-0 px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20">Ended</span>
            </div>
        </div>

        {{-- Winner --}}
        <div class="fade-up rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-900/40 dark:to-emerald-950/20 border border-emerald-200 dark:border-emerald-500/20 p-6 mb-4 shadow-sm dark:shadow-none" style="animation-delay:100ms">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500 dark:bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-900/20 shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs uppercase tracking-[0.15em] text-emerald-600 dark:text-emerald-400 font-bold mb-1">Winning Choice</p>
                    <p class="text-2xl font-black text-emerald-900 dark:text-emerald-100">{{ $winningChoice->label }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs uppercase tracking-[0.12em] text-emerald-600/70 dark:text-emerald-500/70 font-semibold mb-1">Total Pot</p>
                    <p class="text-3xl font-black text-emerald-800 dark:text-emerald-200">{{ number_format($wager->pot, 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="fade-up grid grid-cols-3 gap-3 mb-4" style="animation-delay:140ms">
            @php $statCards = [
                ['label'=>'Participants','value'=>$results->count(),'accent'=>false],
                ['label'=>'Winners','value'=>$results->where('status','won')->count(),'accent'=>true],
                ['label'=>'Total Bets','value'=>number_format($results->sum('total_bet'),0),'accent'=>false],
            ]; @endphp
            @foreach($statCards as $s)
            <div class="rounded-2xl p-4 {{ $s['accent'] ? 'bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20' : 'bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05]' }} shadow-sm dark:shadow-none text-center">
                <p class="text-xs uppercase tracking-[0.12em] {{ $s['accent'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }} font-semibold mb-1">{{ $s['label'] }}</p>
                <p class="text-2xl font-black {{ $s['accent'] ? 'text-emerald-800 dark:text-emerald-200' : 'text-slate-900 dark:text-white' }}">{{ $s['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Results table --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none mb-4" style="animation-delay:180ms">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/[0.05]">
                <p class="text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Detailed Results</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-white/[0.05]">
                            @foreach(['Player','Total Bet','Payout','Profit/Loss','Status'] as $h)
                            <th class="px-5 py-3 text-left text-xs uppercase tracking-[0.12em] font-bold text-slate-400 dark:text-slate-600 {{ $h !== 'Player' ? 'text-right' : '' }} {{ $h === 'Status' ? 'text-center' : '' }}">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                        @forelse($results as $result)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-sm shrink-0">
                                        {{ strtoupper(substr($result['user']->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $result['user']->name }}</p>
                                        @if($wager->creator_id === $result['user']->id)
                                            <span class="text-xs text-slate-400 dark:text-slate-600">Creator</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">{{ number_format($result['total_bet'], 0) }}</td>
                            <td class="px-5 py-4 text-right text-sm font-semibold {{ $result['payout'] > 0 ? 'text-emerald-600 dark:text-emerald-400' :'text-slate-500' }}">{{ number_format($result['payout'], 0) }}</td>
<td class="px-5 py-4 text-right text-sm font-black {{ $result['profit'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">                                {{ result['profit'] > 0 ? '+' : '' }}{{ number_format(
result['profit'], 0) }}
</td>
<td class="px-5 py-4 text-center">
@if($result['status'] === 'won')
<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Won</span>
@else
<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20">Lost</span>
@endif
</td>
</tr>
{{-- Expandable bets --}}
<tr class="bg-slate-50/50 dark:bg-white/[0.01]">
<td colspan="5" class="px-5 py-3">
<div class="ml-12">
<p class="text-xs uppercase tracking-[0.12em] font-bold text-slate-400 dark:text-slate-600 mb-2">Individual Bets</p>
<div class="space-y-1.5">
@foreach($result['bets'] as $bet)
<div class="flex items-center justify-between py-2 px-3 rounded-xl bg-white dark:bg-white/[0.03] border border-slate-100 dark:border-white/[0.05]">
<div class="flex items-center gap-2.5">
<div class="w-2 h-2 rounded-full {{ $bet['is_winner'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}"></div>
<span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $bet['choice'] }}</span>
</div>
<div class="flex items-center gap-5 text-xs">
<span class="text-slate-500">Bet: <strong class="text-slate-900 dark:text-white">{{ number_format($bet['amount'], 0) }}</strong></span>
@if($bet['is_winner'])
<span class="text-emerald-600 dark:text-emerald-400">Payout: <strong>{{ number_format($bet['payout'], 0) }}</strong></span>
<span class="font-black {{ $bet['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">                                                        {{ bet['profit'] >= 0 ? '+' : '' }}{{ number_format(
bet['profit'], 0) }}
</span>
@else
<span class="font-black text-red-600 dark:text-red-400">-{{ number_format($bet['amount'], 0) }}</span>
@endif
</div>
</div>
@endforeach
</div>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No participants found</td>
</tr>
@endforelse
</tbody>
<tfoot class="border-t-2 border-slate-200 dark:border-white/[0.08]">
<tr>
<td class="px-5 py-4 text-xs uppercase tracking-[0.15em] font-black text-slate-500 dark:text-slate-400">Totals</td>
<td class="px-5 py-4 text-right text-sm font-black text-slate-900 dark:text-white">{{ number_format($results->sum('total_bet'), 0) }}</td>
<td class="px-5 py-4 text-right text-sm font-black text-emerald-600 dark:text-emerald-400">{{ number_format($results->sum('payout'), 0) }}</td>
<td class="px-5 py-4 text-right text-sm font-black {{ $results->sum('profit') >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">                                {{ results->sum('profit') >= 0 ? '+' : '' }}{{ number_format(
results->sum('profit'), 0) }}
</td>
<td class="px-5 py-4 text-center text-xs text-slate-500">{{ $results->where('status','won')->count() }}/{{ $results->count() }} won</td>
</tr>
</tfoot>
</table>
</div>
</div>
    {{-- All choices --}}
    <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-6 mb-4 shadow-sm dark:shadow-none" style="animation-delay:220ms">
        <p class="text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-4">All Choices</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($wager->choices as $choice)
            <div class="p-4 rounded-xl border-2 {{ $choice->id === $winningChoice->id ? 'border-emerald-500 dark:border-emerald-500/60 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-white/[0.06] bg-slate-50 dark:bg-white/[0.01]' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $choice->label }}</span>
                    @if($choice->id === $winningChoice->id)
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    @endif
                </div>
                <p class="text-xs text-slate-500">Total: <strong class="text-slate-700 dark:text-slate-300">{{ number_format($choice->total_bet, 0) }}</strong></p>
                <p class="text-xs text-slate-400 dark:text-slate-600 mt-0.5">
                    {{ $wager->pot > 0 ? number_format(($choice->total_bet / $wager->pot) * 100, 1) : 0 }}% of pot
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="fade-up flex gap-3" style="animation-delay:260ms">
        <a href="{{ route('wagers.show', $wager) }}"
            class="flex-1 py-3 text-sm font-bold text-center rounded-xl
                   bg-white dark:bg-white/[0.04] hover:bg-slate-100 dark:hover:bg-white/[0.07]
                   border border-slate-200 dark:border-white/[0.08]
                   text-slate-700 dark:text-slate-300
                   transition-all duration-200">
            View Wager Details
        </a>
        @if(auth()->id() === $wager->creator_id)
        <form action="{{ route('wagers.destroy', $wager) }}" method="POST"
              onsubmit="return confirm('Delete this wager? This cannot be undone.')" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full py-3 text-sm font-bold rounded-xl
                       bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40
                       border border-red-200 dark:border-red-500/20 hover:border-red-400 dark:hover:border-red-500/40
                       text-red-600 dark:text-red-400
                       transition-all duration-200 active:scale-95">
                Delete Wager
            </button>
        </form>
        @endif
    </div>

</div>