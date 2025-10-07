@php
    $compact = $compact ?? false;
@endphp

<div class="relative rounded-xl {{ $compact ? 'p-5' : 'p-6' }} bg-white dark:bg-slate-800/90 border {{ $compact ? 'border-slate-200/60' : 'border-slate-200/80' }} dark:border-slate-700/80 backdrop-blur-sm transition-all duration-300 ease-out cursor-pointer group hover:shadow-xl hover:-translate-y-1 hover:border-emerald-400/60 hover:bg-gradient-to-br hover:from-white hover:to-emerald-50/30 dark:hover:from-slate-800/95 dark:hover:to-emerald-900/10"
    @click="window.location='{{ route('wagers.show', ['wager' => $wager->id]) }}'"
    @keydown.enter.prevent="window.location='{{ route('wagers.show', ['wager' => $wager->id]) }}'" role="button"
    tabindex="0">

    <span
        class="absolute -top-2.5 -right-2.5 z-10 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $wager->status === 'public' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/30' }} group-hover:scale-110 transition-all duration-300">
        @if ($wager->status === 'public')
            <ion-icon class="text-sm" name="globe-outline"></ion-icon>
        @else
            <ion-icon class="text-sm" name="lock-closed-outline"></ion-icon>
        @endif
        {{ ucfirst($wager->status) }}
        @if (Auth::user()->id == $wager->creator_id)
            <div class="border-l-1 border-gray-600 dark:border-gray-700 pl-1 ">
                Your wager
            </div>
        @endif
    </span>

    <div class="space-y-{{ $compact ? '3' : '4' }}">
        <div class="relative">
            <h3
                class="{{ $compact ? 'text-base' : 'text-lg' }} font-bold text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200 {{ $compact ? 'line-clamp-2' : 'line-clamp-1' }}">
                {{ $wager->name }}
            </h3>
            @if (!$compact)
                <div
                    class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600 group-hover:w-full transition-all duration-500 ease-out">
                </div>
            @endif
        </div>

        @if (!empty($wager->description))
            <p
                class="text-slate-600 dark:text-slate-300 {{ $compact ? 'text-xs line-clamp-2' : 'text-sm line-clamp-2' }} leading-relaxed group-hover:text-slate-700 dark:group-hover:text-slate-200 transition-colors duration-200">
                {{ $wager->description }}
            </p>
        @endif

        <div
            class="pt-3 space-y-2.5 border-t border-slate-100 dark:border-slate-700/50 group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-colors duration-300">


            <!-- Ending Time -->
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
        </div>
    </div>

    @if ($compact && $wager->creator_id == Auth::id())
        <div
            class="mt-4 pt-3 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700/50 group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-colors duration-300">
            <a href="{{ route('wagers.edit', $wager) }}" class="flex items-center group/button" @click.stop>
                <button type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors duration-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700/50">
                    <ion-icon class="text-sm" name="create-outline"></ion-icon>
                    <span>Edit</span>
                </button>
            </a>

            <form action="{{ route('wagers.destroy', $wager) }}" method="POST" class="flex items-center group/button"
                @click.stop>
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 transition-colors duration-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700/50">
                    <ion-icon class="text-sm" name="trash-outline"></ion-icon>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    @endif

    <div
        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 bg-gradient-to-br from-emerald-400/5 via-transparent to-blue-400/5 pointer-events-none transition-opacity duration-500">
    </div>
</div>
