@php
    $currentUserId = auth()->id();
    $medals = ['🥇', '🥈', '🥉'];
@endphp

@if($rows->isEmpty())
    <div class="rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-12 text-center shadow-sm">
        <p class="text-slate-500 text-sm">No data yet — be the first!</p>
    </div>
@else
<div class="rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] shadow-sm overflow-hidden">
    @foreach($rows as $i => $row)
    @php
        $rank    = $i + 1;
        $isMe    = $row->id === $currentUserId;
        $rawVal  = $row->{$valueKey} ?? 0;
        $display = match($format) {
            'coins'   => number_format((int) $rawVal) . ' coins',
            'percent' => $rawVal . '%',
            default   => number_format((int) $rawVal),
        };
    @endphp
    <div class="flex items-center gap-4 px-6 py-4 {{ $isMe ? 'bg-emerald-50 dark:bg-emerald-900/20 border-l-2 border-emerald-500' : '' }} {{ $i < count($rows) - 1 ? 'border-b border-slate-100 dark:border-white/[0.04]' : '' }} transition-colors hover:bg-slate-50 dark:hover:bg-white/[0.02]">

        {{-- Rank --}}
        <div class="w-8 text-center shrink-0">
            @if($rank <= 3)
                <span class="text-xl leading-none">{{ $medals[$rank - 1] }}</span>
            @else
                <span class="text-sm font-black text-slate-400 dark:text-slate-600">#{{ $rank }}</span>
            @endif
        </div>

        {{-- Avatar --}}
        <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-sm font-black
            {{ $isMe
                ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/40'
                : 'bg-slate-100 dark:bg-white/[0.05] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/[0.07]' }}">
            {{ strtoupper(substr($row->name, 0, 1)) }}
        </div>

        {{-- Name --}}
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm text-slate-900 dark:text-white truncate">
                {{ $row->name }}
                @if($isMe)
                    <span class="ml-1.5 text-[0.6rem] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">You</span>
                @endif
            </p>
            @if($format === 'percent' && isset($row->total_bets))
                <p class="text-xs text-slate-400 mt-0.5">{{ $row->wins }}/{{ $row->total_bets }} bets won</p>
            @endif
        </div>

        {{-- Value --}}
        <div class="text-right shrink-0">
            @if($rank === 1)
                <p class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ $display }}</p>
            @else
                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $display }}</p>
            @endif
        </div>

    </div>
    @endforeach
</div>
@endif
