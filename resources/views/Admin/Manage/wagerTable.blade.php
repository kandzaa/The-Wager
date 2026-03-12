<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Management</p>
        <h2 class="text-xl font-black tracking-tight text-white">Wagers</h2>
    </div>
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
        Live
    </div>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-white/[0.06]">
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">ID</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Name</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Creator</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3 hidden md:table-cell">Description</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Players</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Pot</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Status</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/[0.04]">
            @forelse ($wager as $wagerItem)
                <tr class="group hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-3 text-slate-600 dark:text-slate-500 font-mono text-xs">#{{ $wagerItem->id }}</td>
                    <td class="py-3 px-3 font-semibold text-white">{{ $wagerItem->name }}</td>
                    <td class="py-3 px-3 text-slate-400">{{ $wagerItem->creator?->name ?? '—' }}</td>
                    <td class="py-3 px-3 text-slate-500 max-w-[200px] truncate hidden md:table-cell">{{ $wagerItem->description ?? '—' }}</td>
                    <td class="py-3 px-3 text-slate-400">{{ $wagerItem->max_players }}</td>
                    <td class="py-3 px-3">
                        <span class="text-emerald-400 font-semibold">{{ number_format($wagerItem->pot) }}</span>
                    </td>
                    <td class="py-3 px-3">
                        @if($wagerItem->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-1 h-1 rounded-full bg-emerald-400"></span>Active
                            </span>
                        @elseif($wagerItem->status === 'ended')
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                Ended
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                {{ ucfirst($wagerItem->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('wagers.show', ['wager' => $wagerItem->id]) }}"
                               class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/[0.06] transition-all" title="View">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.Manage.wagers.edit', $wagerItem->id) }}"
                               class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-400 hover:bg-emerald-500/[0.08] transition-all" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.Manage.wagers.destroy', $wagerItem->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this wager?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-all" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-slate-600 text-sm">No wagers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>