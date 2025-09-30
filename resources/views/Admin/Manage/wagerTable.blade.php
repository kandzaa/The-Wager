<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">All wagers</h2>
</div>
<table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
    <thead class="bg-slate-100/70 dark:bg-slate-800/40">
        <tr>
            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                ID</th>
            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Theme</th>
            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Creator name</th>

            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Descriptions</th>
            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Max players</th>

            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Pot</th>
            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Visibility</th>

            <th
                class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                Function</th>
        </tr>
    </thead>
    <tbody class="bg-white/70 dark:bg-slate-900/40 divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($wager as $wagerItem)
            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                    {{ $wagerItem->id }}</td>
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">
                    {{ $wagerItem->name }}</td>
                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ $wagerItem->creator?->name ?? 'Unknown' }}</td>
                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ $wagerItem->description ?? 'No description' }}</td>

                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ $wagerItem->max_players }}</td>


                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ $wagerItem->pot }}</td>

                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ $wagerItem->status }}</td>
                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                    <form action="{{ route('admin.Manage.wagers.destroy', $wagerItem->id) }}" method="POST"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 mx-1"
                            onclick="return confirm('Are you sure you want to delete this wager?')">
                            <ion-icon name="trash" class="text-xl"></ion-icon>
                        </button>
                    </form>
                    <span class="mx-1">|</span>
                    <a href="{{ route('admin.Manage.wagers.edit', $wagerItem->id) }}"
                        class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 mx-1"><ion-icon name="create"
                            class="text-xl"></ion-icon></a>
                    <span class="mx-1">|</span>
                    <a href="{{ route('wagers.show', ['wager' => $wagerItem->id]) }}"
                        class="inline-block dark:text-white text-black rounded-md mx-1"><ion-icon name="search"
                            class="text-xl"></ion-icon></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                    No wagers found.
                </td>
            </tr>
        @endforelse
    </tbody>

</table>
