<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                        Wager History
                    </h1>
                    <p class="mt-3 text-lg text-slate-600 dark:text-slate-300">
                        Review your past wagers and their outcomes
                    </p>
                </div>

                <!-- Tab Navigation -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex rounded-lg border border-slate-200 dark:border-slate-700 p-1 bg-slate-100 dark:bg-slate-800">
                        <button @click="activeTab = 'yourWagers'" 
                                :class="activeTab === 'yourWagers' ? 'bg-white dark:bg-slate-700 shadow-sm' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                class="px-6 py-2 text-sm font-medium rounded-md transition-all duration-200"
                                :class="activeTab === 'yourWagers' ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'">
                            Your Wagers
                        </button>
                        <button @click="activeTab = 'publicWagers'" 
                                :class="activeTab === 'publicWagers' ? 'bg-white dark:bg-slate-700 shadow-sm' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                class="px-6 py-2 text-sm font-medium rounded-md transition-all duration-200"
                                :class="activeTab === 'publicWagers' ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'">
                            Public Wagers
                        </button>
                    </div>
                </div>
            </div>

            <div x-data="{ activeTab: 'yourWagers' }">
                <!-- Your Wagers Tab -->
                <div x-show="activeTab === 'yourWagers'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100">
                    @if ($userWagers->isEmpty())
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-lg font-medium text-slate-900 dark:text-white">No wager history yet</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Your ended wagers will appear here once you participate in some.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('wagers.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Browse Wagers
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($userWagers as $wager)
                                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white line-clamp-2">
                                                {{ $wager->name }}
                                            </h3>
                                            <span class="ml-2 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 whitespace-nowrap">
                                                Ended
                                            </span>
                                        </div>
                                        
                                        @if($wager->description)
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                                                {{ $wager->description }}
                                            </p>
                                        @endif

                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $wager->creator->name ?? 'Unknown' }}
                                            </div>
                                            <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $wager->players_count }} {{ $wager->players_count == 1 ? 'player' : 'players' }}
                                            </div>
                                            <div class="flex items-center text-sm font-semibold text-purple-600 dark:text-purple-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                ${{ number_format($wager->pot ?? 0, 2) }}
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('history.wager.show', $wager) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                                                View Results
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $userWagers->links() }}
                        </div>
                    @endif
                </div>

                <!-- Public Wagers Tab -->
                <div x-show="activeTab === 'publicWagers'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100">
                    @if ($publicWagers->isEmpty())
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-lg font-medium text-slate-900 dark:text-white">No public wagers found</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                There are no public ended wagers at the moment.
                            </p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($publicWagers as $wager)
                                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white line-clamp-2">
                                                {{ $wager->name }}
                                            </h3>
                                            <span class="ml-2 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 whitespace-nowrap">
                                                Ended
                                            </span>
                                        </div>
                                        
                                        @if($wager->description)
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                                                {{ $wager->description }}
                                            </p>
                                        @endif

                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $wager->creator->name ?? 'Unknown' }}
                                            </div>
                                            <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $wager->players_count }} {{ $wager->players_count == 1 ? 'player' : 'players' }}
                                            </div>
                                            <div class="flex items-center text-sm font-semibold text-purple-600 dark:text-purple-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                ${{ number_format($wager->pot ?? 0, 2) }}
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('history.wager.show', $wager) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                                                View Results
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $publicWagers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>