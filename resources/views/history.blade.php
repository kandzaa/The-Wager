<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">Wager History</h1>

            <!-- Your Wagers -->
            <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4">Your Wagers</h2>
            @if ($userWagers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach ($userWagers as $wager)
                        <a href="{{ route('history.show', $wager) }}"
                            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition block">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $wager->name }}</h3>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Ended</span>
                            </div>
                            @if ($wager->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $wager->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>{{ $wager->creator->name ?? 'Unknown' }}</span>
                                <span>{{ $wager->players_count }} players</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{ $userWagers->links() }}
            @else
                <p class="text-slate-500 dark:text-slate-400 mb-10">You haven't participated in any ended wagers yet.</p>
            @endif

            <!-- Public Wagers -->
            <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-4 mt-10">Public Wagers</h2>
            @if ($publicWagers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($publicWagers as $wager)
                        <a href="{{ route('history.show', $wager) }}"
                            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition block">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $wager->name }}</h3>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Ended</span>
                            </div>
                            @if ($wager->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $wager->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>{{ $wager->creator->name ?? 'Unknown' }}</span>
                                <span>{{ $wager->players_count }} players</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{ $publicWagers->links() }}
            @else
                <p class="text-slate-500 dark:text-slate-400">No public wagers available.</p>
            @endif

        </div>
    </div>
</x-app-layout>