<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('wagers.index') }}"
                    class="inline-flex items-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 mb-4 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Wagers
                </a>

                <div
                    class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg rounded-2xl shadow-xl border border-slate-200/60 dark:border-slate-700/60 p-8">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1
                                class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
                                {{ $wager->name }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-300 mt-2">{{ $wager->description }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                @if($wager->ended_at)
                                    Ended on {{ $wager->ended_at->format('F j, Y \a\t g:i A') }}
                                @else
                                    Ended recently
                                @endif
                            </p>
                        </div>
                        <div class="px-4 py-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <span class="text-sm font-medium text-red-800 dark:text-red-200">ENDED</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Winning Choice -->
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800/40 rounded-2xl shadow-lg border border-emerald-200/60 dark:border-emerald-700/60 p-6 mb-8">
                <div class="flex items-center gap-4">
                    <div
                        class="flex-shrink-0 w-16 h-16 bg-emerald-500 dark:bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-emerald-800 dark:text-emerald-200">Winning Choice</h2>
                        <p class="text-2xl font-bold text-emerald-900 dark:text-emerald-100 mt-1">
                            {{ $winningChoice->label }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-emerald-700 dark:text-emerald-300">Total Pot</p>
                        <p class="text-3xl font-bold text-emerald-900 dark:text-emerald-100">
                            {{ number_format($wager->pot, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Total Participants</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $results->count() }}</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Winners</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ $results->where('status', 'won')->count() }}
                    </p>
                </div>
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Total Bets</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">
                        {{ number_format($results->sum('total_bet'), 0) }}
                    </p>
                </div>
            </div>

            <!-- Results Table -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Detailed Results</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-100 dark:bg-slate-900/70">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Player</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Total Bet</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Payout</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Profit/Loss</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($results as $result)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($result['user']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-slate-100">
                                                    {{ $result['user']->name }}</p>
                                                @if ($wager->creator_id === $result['user']->id)
                                                    <span
                                                        class="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full">Creator</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-900 dark:text-slate-100">
                                        {{ number_format($result['total_bet'], 0) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-medium {{ $result['payout'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                                        {{ number_format($result['payout'], 0) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-bold {{ $result['profit'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $result['profit'] > 0 ? '+' : '' }}{{ number_format($result['profit'], 0) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($result['status'] === 'won')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Won
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Lost
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Expandable row for individual bets -->
                                <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                    <td colspan="5" class="px-6 py-4">
                                        <div class="ml-12">
                                            <p
                                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">
                                                Individual Bets</p>
                                            <div class="space-y-2">
                                                @foreach ($result['bets'] as $bet)
                                                    <div
                                                        class="flex items-center justify-between py-2 px-4 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                                        <div class="flex items-center gap-3">
                                                            @if ($bet['is_winner'])
                                                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                                            @else
                                                                <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                                                            @endif
                                                            <span
                                                                class="font-medium text-slate-900 dark:text-slate-100">{{ $bet['choice'] }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-6 text-sm">
                                                            <span class="text-slate-600 dark:text-slate-400">
                                                                Bet: <strong
                                                                    class="text-slate-900 dark:text-slate-100">{{ number_format($bet['amount'], 0) }}</strong>
                                                            </span>
                                                            @if ($bet['is_winner'])
                                                                <span class="text-emerald-600 dark:text-emerald-400">
                                                                    Payout:
                                                                    <strong>{{ number_format($bet['payout'], 0) }}</strong>
                                                                </span>
                                                                <span
                                                                    class="font-semibold {{ $bet['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                                    {{ $bet['profit'] >= 0 ? '+' : '' }}{{ number_format($bet['profit'], 0) }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="text-red-600 dark:text-red-400 font-semibold">
                                                                    -{{ number_format($bet['amount'], 0) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        No participants found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-100 dark:bg-slate-900/70 font-semibold">
                            <tr>
                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100">TOTALS</td>
                                <td class="px-6 py-4 text-right text-slate-900 dark:text-slate-100">
                                    {{ number_format($results->sum('total_bet'), 0) }}
                                </td>
                                <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($results->sum('payout'), 0) }}
                                </td>
                                <td
                                    class="px-6 py-4 text-right {{ $results->sum('profit') >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $results->sum('profit') >= 0 ? '+' : '' }}{{ number_format($results->sum('profit'), 0) }}
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 dark:text-slate-400">
                                    {{ $results->where('status', 'won')->count() }}/{{ $results->count() }} won
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- All Choices Summary -->
            <div
                class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">All Choices</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($wager->choices as $choice)
                        <div
                            class="p-4 rounded-lg border {{ $choice->id === $winningChoice->id ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="font-semibold text-slate-900 dark:text-slate-100">{{ $choice->label }}</span>
                                @if ($choice->id === $winningChoice->id)
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Total Bets: <strong
                                    class="text-slate-900 dark:text-slate-100">{{ number_format($choice->total_bet, 0) }}</strong>
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                {{ $wager->pot > 0 ? number_format(($choice->total_bet / $wager->pot) * 100, 1) : 0 }}%
                                of pot
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex gap-4">
                <a href="{{ route('wagers.show', $wager) }}"
                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-200 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-slate-800 dark:text-white hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    View Wager Details
                </a>

                @if (auth()->id() === $wager->creator_id)
                    <form action="{{ route('wagers.destroy', $wager) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this wager? This action cannot be undone.');"
                        class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-semibold text-white hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Wager
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
