<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('history.index') }}"
                    class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to History
                </a>
            </div>

            <!-- Header -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ $wager->name }}
                        </h1>
                        @if ($wager->description)
                            <p class="text-slate-600 dark:text-slate-400">
                                {{ $wager->description }}
                            </p>
                        @endif
                    </div>
                    <span
                        class="ml-4 px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                        Ended
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Creator</div>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $wager->creator->name ?? 'Unknown' }}
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total Players</div>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $stats['total_players'] }}
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total Pot</div>
                        <div class="text-sm font-semibold text-purple-600 dark:text-purple-400">
                            ${{ number_format($stats['total_pot'], 2) }}
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Winners</div>
                        <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ $stats['winners_count'] }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Winning Choice -->
            @if ($wager->winningChoice)
                <div
                    class="bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-200 dark:border-emerald-800 rounded-xl p-6 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-emerald-500 dark:bg-emerald-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-emerald-800 dark:text-emerald-300">Winning Choice</div>
                            <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">
                                {{ $wager->winningChoice->label }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Choice Distribution -->
            @if (isset($stats['choice_distribution']) && $stats['choice_distribution']->isNotEmpty())
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Bet Distribution</h2>
                    <div class="space-y-3">
                        @foreach ($stats['choice_distribution'] as $choice)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $choice['label'] }}
                                        </span>
                                        @if ($wager->winning_choice_id == $choice['id'])
                                            <span
                                                class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium rounded">
                                                Winner
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        ${{ number_format($choice['total_bet'], 2) }} ({{ $choice['percentage'] }}%)
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300 {{ $wager->winning_choice_id == $choice['id'] ? 'bg-emerald-500' : 'bg-blue-500' }}"
                                        style="width: {{ $choice['percentage'] }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Results Table -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Player Results</h2>
                </div>

                @if ($userResults && count($userResults) > 0)
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
                                        Payout
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Profit/Loss
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($userResults as $result)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                                    {{ substr($result['user_name'], 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                        {{ $result['user_name'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                            ${{ number_format($result['total_bet'], 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-white">
                                            ${{ number_format($result['payout'], 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $result['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $result['profit'] >= 0 ? '+' : '' }}${{ number_format($result['profit'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium {{ $result['status'] === 'won' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                                {{ ucfirst($result['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button onclick="toggleDetails({{ $loop->index }})"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                                                View Bets
                                            </button>
                                        </td>
                                    </tr>
                                    <tr id="details-{{ $loop->index }}"
                                        class="hidden bg-slate-50 dark:bg-slate-900/50">
                                        <td colspan="6" class="px-6 py-4">
                                            <div class="space-y-2">
                                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                                    Bet Details:</h4>
                                                @foreach ($result['bets'] as $bet)
                                                    <div
                                                        class="flex items-center justify-between py-2 px-3 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700">
                                                        <div class="flex items-center gap-3">
                                                            @if ($bet['is_winner'])
                                                                <span
                                                                    class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                            @else
                                                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                                            @endif
                                                            <span class="text-sm text-slate-900 dark:text-white">
                                                                {{ $bet['choice'] }}
                                                            </span>
                                                        </div>
                                                        <div class="text-sm text-slate-600 dark:text-slate-400">
                                                            Bet: ${{ number_format($bet['amount'], 2) }} •
                                                            Payout: ${{ number_format($bet['payout'], 2) }} •
                                                            <span
                                                                class="{{ $bet['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                                {{ $bet['profit'] >= 0 ? '+' : '' }}${{ number_format($bet['profit'], 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-sm text-slate-500 dark:text-slate-400">No betting data available for this wager.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(index) {
            const detailsRow = document.getElementById(`details-${index}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
