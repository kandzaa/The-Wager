<div class="rounded-xl p-5 shadow-sm hover:shadow-md transition bg-slate-900/40 border border-slate-800 backdrop-blur">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">{{ $wager->name }}</h3>
        </div>
        <span
            class="text-2xs px-2 py-1 rounded-full border {{ $wager->status === 'public' ? 'bg-emerald-900/30 text-emerald-200 border-emerald-800' : 'bg-amber-900/30 text-amber-200 border-amber-800' }}">
            {{ ucfirst($wager->status) }}
        </span>
    </div>

    @if (!empty($wager->description))
        <p class="text-slate-300 mt-3 text-sm leading-relaxed">{{ $wager->description }}</p>
    @endif

    @if ($wager->choices && $wager->choices->count())
        <div class="mt-4">
            <h4 class="text-xs font-medium text-slate-400 mb-2">Choices</h4>
            <div class="flex flex-wrap gap-2">
                @foreach ($wager->choices as $choice)
                    <span
                        class="text-2xs border rounded-md px-2 py-1 bg-slate-800 text-slate-200 border-slate-700">{{ $choice->label }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between text-sm text-slate-400">
        <span>Max players: {{ $wager->max_players }}</span>
    </div>
    <div class="mt-4 flex items-center justify-between text-sm">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-500 transition">Join</button>
    </div>
    <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
        <span>Ends {{ $wager->ending_time->diffForHumans() }}</span>
    </div>
    @if ($wager->creator_id == Auth::user()->id)
        <div class="mt-3 flex gap-2">
            <form action="{{ route('wagers.destroy', $wager) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-500 transition">Delete</button>
            </form>

            <button
                @click="
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
                class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-500 transition">
                Edit
            </button>
        </div>
    @endif
</div>
