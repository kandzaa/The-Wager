<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">All Users</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                                <thead class="bg-slate-100/70 dark:bg-slate-800/40">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            Joined</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            Function</th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white/70 dark:bg-slate-900/40 divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse ($users as $user)
                                        <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                                                {{ $user->id }}</td>
                                            <td
                                                class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">
                                                {{ $user->name }}</td>

                                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                                {{ $user->email }}</td>

                                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $user->created_at?->diffForHumans() }}</td>

                                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $user->balance }}</td>

                                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $user->role }}</td>

                                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                                <form action="{{ route('admin.Manage.users.destroy', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400"
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete
                                                    </button>
                                                </form>
                                                <span class="mx-1">|</span>
                                                <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
                                                    class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400">Edit</a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No
                                                users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
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
                                        Players</th>
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
                            <tbody
                                class="bg-white/70 dark:bg-slate-900/40 divide-y divide-slate-200 dark:divide-slate-800">
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
                                            @php $players = $wagerItem->players ?? []; @endphp
                                            @if (is_array($players))
                                                @foreach ($players as $player)
                                                    {{ is_array($player) ? $player['name'] ?? json_encode($player) : (is_object($player) ? $player->name ?? (string) $player : (string) $player) }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ (string) $players }}
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $wagerItem->pot }}</td>

                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $wagerItem->status }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            <form action="{{ route('admin.Manage.wagers.destroy', $wagerItem->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 dark:hover:text-red-400 mx-1">
                                                    Delete
                                                </button>
                                            </form>
                                            <span class="mx-1">|</span>
                                            <a href="{{ route('admin.Manage.wagers.edit', $wagerItem->id) }}"
                                                class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 mx-1">Edit</a>
                                            <span class="mx-1">|</span>
                                            <a href="{{ route('wager.show', $wagerItem->id) }}"
                                                class="inline-block text-white rounded-md mx-1">Show</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No
                                            wagers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>




                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
