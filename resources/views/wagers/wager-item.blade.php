@php
    $compact = $compact ?? false;
    $wagerLink = $wager->status === 'ended' ? route('wagers.results', $wager) : route('wagers.show', $wager);
@endphp

<div class="wager-item group relative rounded-2xl
            bg-white dark:bg-white/[0.03]
            border border-slate-200 dark:border-white/[0.07]
            hover:border-emerald-400 dark:hover:border-emerald-500/40
            shadow-sm dark:shadow-none
            hover:shadow-md dark:hover:shadow-none
            transition-all duration-300 cursor-pointer
            {{ $compact ? 'p-4' : 'p-5' }}"
    data-name="{{ $wager->name ?? '' }}"
    data-creator="{{ $wager->creator->name ?? 'Unknown' }}"
    data-status="{{ $wager->status ?? 'active' }}"
    data-privacy="{{ $wager->privacy ?? 'public' }}"
    data-buyin="{{ (int)($wager->buy_in ?? 0) }}"
    @click="window.location='{{ $wagerLink }}'"
    role="button" tabindex="0">

    {{-- Status badge --}}
    <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5">
        @if(isset($wager->status))
            @if($wager->status === 'ended')
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                    Ended
                </span>
            @elseif(($wager->privacy ?? 'public') === 'public')
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                    Public
                </span>
            @else
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                    Private
                </span>
            @endif
        @endif
        @auth
            @if(isset($wager->creator_id) && auth()->id() == $wager->creator_id)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-white/[0.06] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/[0.08]">
                    Yours
                </span>
            @endif
        @endauth
    </div>

    {{-- Content --}}
    <div class="pr-16">
        <h3 class="font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors duration-200 {{ $compact ? 'text-sm line-clamp-2' : 'text-base line-clamp-1' }} mb-1">
            {{ $wager->name ?? 'Unnamed Wager' }}
        </h3>

        @if(!empty($wager->description))
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-3">{{ $wager->description }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 pt-3 border-t border-slate-100 dark:border-white/[0.05]">
            @if(isset($wager->ending_time) && $wager->ending_time instanceof \Carbon\Carbon)
                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-amber-600 dark:text-amber-400 font-medium">{{ $wager->ending_time->diffForHumans() }}</span>
                </div>
            @endif

            @if(isset($wager->creator->name))
                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $wager->creator->name }}
                </div>
            @endif

            @if(isset($wager->max_players))
                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $wager->players_count ?? 0 }}/{{ $wager->max_players }}
                </div>
            @endif
            @if(isset($wager->buy_in) && (int)$wager->buy_in > 0)
                <div class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-500/20 text-xs font-semibold text-amber-700 dark:text-amber-400">
                    <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-3 h-3 shrink-0 dark:invert">
                    {{ number_format((int)$wager->buy_in) }}
                </div>
            @endif
        </div>
    </div>

    {{-- Compact edit/delete --}}
    @if($compact && isset($wager->creator_id) && auth()->check() && $wager->creator_id == auth()->id())
        <div class="mt-3 pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-white/[0.05]" @click.stop>
            <a href="{{ route('wagers.edit', $wager) }}"
               class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 bg-slate-50 dark:bg-white/[0.04] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border border-slate-200 dark:border-white/[0.06] rounded-lg transition-all duration-200">
                Edit
            </a>
            <div x-data="{ open: false }">
                <button type="button" @click="open = true"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 bg-slate-50 dark:bg-white/[0.04] hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.06] rounded-lg transition-all duration-200">
                    Delete
                </button>
                <div x-show="open" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    @click.self="open = false">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xl w-80 max-w-full mx-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Delete wager?</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">This cannot be undone. All bets and players will be removed.</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-white/[0.06] hover:bg-slate-200 dark:hover:bg-white/[0.10] rounded-lg transition-all duration-200">
                                Cancel
                            </button>
                            <form action="{{ route('wagers.destroy', $wager) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-all duration-200">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>