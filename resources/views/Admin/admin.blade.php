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
                                        Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                        creator</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                        Function</th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white/70 dark:bg-slate-900/40 divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($wagers as $wager)
                                    <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                        <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                                            {{ $wager->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">
                                            {{ $wager->name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $wager->creator_id }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
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
