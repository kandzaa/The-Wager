<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('history') }}"
                    class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to History
                </a>
            </div>

            <!-- Wager Header -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $wager->name }}</h1>
                        @if ($wager->description)
                            <p class="mt-2 text-slate-600 dark:text-slate-400">{{ $wager->description }}</p>
                        @endif
                    </div>
                    <span
                        class="px-4 py-2 rounded-full text-sm font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                        Ended
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Total Pot</span>
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">
                            ${{ number_format($stats['total_pot'], 2) }}</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Total Players</span>
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $stats['total_players'] }}
                        </p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Winners</span>
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $stats['winners_count'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Winning Choice -->
            @if ($wager->winning_choice_id)
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl shadow-sm p-6 mb-6 border border-green-200 dark:border-green-800">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <h2 class="text-xl font-bold text-green-900 dark:text-green-100">Winning Choice</h2>
                    </div>
                    <p class="text-lg text-green-800 dark:text-green-200">Choice #{{ $wager->winning_choice_id }}</p>
                </div>
            @else
                <div class="bg-slate-100 dark:bg-slate-800 rounded-xl shadow-sm p-6 mb-6">
                    <p class="text-center text-slate-600 dark:text-slate-400">No winning choice was selected</p>
                </div>
            @endif

            <!-- Winners List -->
            @if ($stats['winning_bets']->isNotEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Winners</h2>
                    <div class="space-y-3">
                        @foreach ($stats['winning_bets'] as $bet)
                            <div
                                class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($bet->wagerPlayer->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">
                                            {{ $bet->wagerPlayer->user->name ?? 'Unknown User' }}
                                        </p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Bet: ${{ number_format($bet->bet_amount, 2) }}
                                        </p>
                                    </div>
                                </div>
                                @if ($bet->payout)
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                            +${{ number_format($bet->payout, 2) }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Payout</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All Participants -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">All Participants</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Player
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Total Bet
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Result
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Payout
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($wager->players as $player)
                                @php
                                    $totalBet = $player->bets->sum('bet_amount');
                                    $totalPayout = $player->bets->sum('payout');
                                    $isWinner = $player->bets->where('is_win', true)->isNotEmpty();
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                                {{ substr($player->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-slate-900 dark:text-white">
                                                {{ $player->user->name ?? 'Unknown User' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                        ${{ number_format($totalBet, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($isWinner)
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                Won
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                Lost
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        @if ($totalPayout > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                +${{ number_format($totalPayout, 2) }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600">
                                                $0.00
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No participants found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
