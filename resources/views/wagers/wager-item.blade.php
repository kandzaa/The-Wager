<div class="rounded-xl p-5 shadow-sm bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 backdrop-blur transition transform duration-200 ease-out cursor-pointer group hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-500/50"
    @click="window.location='{{ route('wagers.show', ['id' => $wager->id]) }}'"
    @keydown.enter.prevent="window.location='{{ route('wagers.show', ['id' => $wager->id]) }}'" role="button"
    tabindex="0">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $wager->name }}</h3>
        </div>
        <span
            class="text-2xs px-2 py-1 rounded-full border {{ $wager->status === 'public' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200 border-emerald-300 dark:border-emerald-800' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-200 border-amber-300 dark:border-amber-800' }}">
            {{ ucfirst($wager->status) }}
        </span>
    </div>

    @if (!empty($wager->description))
        <p class="text-slate-600 dark:text-slate-300 mt-3 text-sm leading-relaxed">{{ $wager->description }}</p>
    @endif


    <div class="mt-4 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
        <span>Max players: {{ $wager->max_players }}</span>
    </div>
    <div class="mt-4 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <span>Ends {{ $wager->ending_time->diffForHumans() }}</span>
    </div>
    @if ($wager->creator_id == Auth::user()->id)
        <div class="mt-3 flex gap-2">
            <form action="{{ route('wagers.destroy', $wager) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wager?');">
                @csrf
                @method('DELETE')
                <button type="submit" @click.stop
                    class="px-4 py-2 bg-red-600 dark:bg-rose-600 text-white rounded-md hover:bg-red-500 dark:hover:bg-rose-500 transition">Delete</button>
            </form>

            <button
                @click.stop="
                    $dispatch('edit-wager', {
                        id: {{ $wager->id }},
                        name: @js($wager->name),
                        description: @js($wager->description),
                        max_players: {{ (int) $wager->max_players }},
                        visibility: @js($wager->status === 'public' ? 'public' : 'private'),
                        ending_time_local: @js(optional($wager->ending_time)->format('Y-m-d\TH:i')),
                        choices: @js($wager->choices->pluck('label'))
                    })
                "
                class="px-4 py-2 bg-blue-600 dark:bg-sky-600 text-white rounded-md hover:bg-blue-500 dark:hover:bg-sky-500 transition">
                Edit
            </button>
        </div>
    @endif
</div>
