@php
    $compact = $compact ?? false;
    $wagerLink = $wager->status === 'ended' ? route('wagers.results', $wager) : route('wagers.show', $wager);
@endphp

<div class="wager-item relative rounded-xl {{ $compact ? 'p-5' : 'p-6' }} bg-white dark:bg-slate-800/90 border {{ $compact ? 'border-slate-200/60' : 'border-slate-200/80' }} dark:border-slate-700/80 backdrop-blur-sm transition-all duration-300 ease-out cursor-pointer group hover:shadow-xl hover:-translate-y-1 hover:border-emerald-400/60 hover:bg-gradient-to-br hover:from-white hover:to-emerald-50/30 dark:hover:from-slate-800/95 dark:hover:to-emerald-900/10"
    data-name="{{ $wager->name ?? '' }}"
    data-creator="{{ isset($wager->creator) && isset($wager->creator->name) ? $wager->creator->name : 'Unknown' }}"
    @click="window.location='{{ $wagerLink }}'" @keydown.enter.prevent="window.location='{{ $wagerLink }}'"
    role="button" tabindex="0">

    <!-- Status Badges -->
    <div class="absolute -top-2.5 -right-2.5 z-10 flex flex-col items-end gap-1">
        @if (isset($wager->status))
            @if ($wager->status === 'ended')
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/30 group-hover:scale-110 transition-all duration-300">
                    <ion-icon class="text-sm" name="flag-outline"></ion-icon> Ended
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $wager->status === 'public' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/30' }} group-hover:scale-110 transition-all duration-300">
                    @if ($wager->status === 'public')
                        <ion-icon class="text-sm" name="globe-outline"></ion-icon>
                    @else
                        <ion-icon class="text-sm" name="lock-closed-outline"></ion-icon>
                    @endif
                    {{ ucfirst($wager->status) }}
                </span>
            @endif
        @endif

        @auth
            @if (isset($wager->creator_id) && auth()->id() == $wager->creator_id)
                <span
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700/80 text-slate-700 dark:text-slate-200 shadow-sm group-hover:scale-105 transition-all duration-300">
                    <ion-icon class="text-xs" name="star-outline"></ion-icon> Your wager
                </span>
            @endif
        @endauth
    </div>

    <!-- Main Content -->
    <div class="space-y-{{ $compact ? '3' : '4' }}">
        <div class="relative">
            <h3
                class="{{ $compact ? 'text-base' : 'text-lg' }} font-bold text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200 {{ $compact ? 'line-clamp-2' : 'line-clamp-1' }}">
                {{ $wager->name ?? 'Unnamed Wager' }}
            </h3>
            @if (!$compact)
                <div
                    class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600 group-hover:w-full transition-all duration-500 ease-out">
                </div>
            @endif
        </div>

        @if (!empty($wager->description ?? ''))
            <p
                class="text-slate-600 dark:text-slate-300 {{ $compact ? 'text-xs line-clamp-2' : 'text-sm line-clamp-2' }} leading-relaxed group-hover:text-slate-700 dark:group-hover:text-slate-200 transition-colors duration-200">
                {{ $wager->description }}
            </p>
        @endif

        <div
            class="pt-3 space-y-2.5 border-t border-slate-100 dark:border-slate-700/50 group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-colors duration-300">
            @if (isset($wager->ending_time) && $wager->ending_time instanceof \Carbon\Carbon)
                <div class="flex items-center {{ $compact ? 'text-xs' : 'text-sm' }}">
                    <div
                        class="flex items-center justify-center w-8 h-8 mr-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50 group-hover:bg-amber-50 dark:group-hover:bg-amber-900/30 transition-colors duration-300">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span
                        class="font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                        Ends <span
                            class="text-amber-600 dark:text-amber-400">{{ $wager->ending_time->diffForHumans() }}</span>
                    </span>
                </div>
            @elseif (isset($wager->ending_time))
                <div class="flex items-center {{ $compact ? 'text-xs' : 'text-sm' }}">
                    <div
                        class="flex items-center justify-center w-8 h-8 mr-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-slate-600 dark:text-slate-400">
                        Ends {{ $wager->ending_time }}
                    </span>
                </div>
            @endif

            @if (isset($wager->creator) && isset($wager->creator->name))
                <div class="flex items-center {{ $compact ? 'text-xs' : 'text-sm' }}">
                    <div
                        class="flex items-center justify-center w-8 h-8 mr-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                        By {{ $wager->creator->name }}
                    </span>
                </div>
            @endif

            @php
                $maxPlayers = $wager->max_players ?? null;
                $playersCount = $wager->players_count ?? 0;
            @endphp

            @if ($maxPlayers !== null && $playersCount !== null)
                <div class="flex items-center {{ $compact ? 'text-xs' : 'text-sm' }}">
                    <div
                        class="flex items-center justify-center w-8 h-8 mr-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-medium text-slate-600 dark:text-slate-400">
                        {{ $playersCount }} / {{ $maxPlayers }} players
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit/Delete Actions for Compact View (Your Wagers) -->
    @if ($compact && isset($wager->creator_id) && auth()->check() && $wager->creator_id == auth()->id())
        <div
            class="mt-4 pt-3 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700/50 group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-colors duration-300">
            @if (isset($wager->id))
                <a href="{{ route('wagers.edit', $wager) }}" class="flex items-center" @click.stop>
                    <button type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors duration-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700/50">
                        <ion-icon class="text-sm" name="create-outline"></ion-icon>
                        <span>Edit</span>
                    </button>
                </a>
            @endif

            @if (isset($wager->id))
                <form action="{{ route('wagers.destroy', $wager) }}" method="POST" class="flex items-center"
                    @click.stop>
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 transition-colors duration-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700/50"
                        onclick="return confirm('Are you sure you want to delete this wager?')">
                        <ion-icon class="text-sm" name="trash-outline"></ion-icon>
                        <span>Delete</span>
                    </button>
                </form>
            @endif
        </div>
    @endif

    <!-- Hover Overlay -->
    <div
        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 bg-gradient-to-br from-emerald-400/5 via-transparent to-blue-400/5 pointer-events-none transition-opacity duration-500">
    </div>
</div>
