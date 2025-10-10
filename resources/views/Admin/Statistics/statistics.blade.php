<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Wager Statistics</h1>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Total Wagers -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-lg bg-emerald-100/80 dark:bg-emerald-900/30">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Wagers
                                        </p>
                                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($stats['total_wagers'] ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Wagers -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-lg bg-blue-100/80 dark:bg-blue-900/30">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Active Wagers
                                        </p>
                                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($stats['active_wagers'] ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-lg bg-amber-100/80 dark:bg-amber-900/30">
                                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 5v1m0 0v1m0 0v1m0-4v1m0 5.68c-1.325.435-2.5.59-3.5.59-3.5 0-5.5-1.5-5.5-5.5 0-4 2-5.5 5.5-5.5 1.5 0 2.5.5 3.5 1" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Amount
                                        </p>
                                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                                            ${{ number_format($stats['total_amount'] ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Users -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-lg bg-purple-100/80 dark:bg-purple-900/30">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Users
                                        </p>
                                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($stats['total_users'] ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <!-- Wagers Over Time -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Wagers Over Time
                                </h3>
                                <div class="h-80">
                                    <canvas id="wagersOverTimeChart"></canvas>
                                </div>
                            </div>

                            <!-- Wagers by Status -->
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Wagers by Status
                                </h3>
                                <div class="h-80">
                                    <canvas id="wagersByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div
                            class="bg-white/80 dark:bg-slate-800/80 p-6 rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700/60">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Recent Activity</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                Wager</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                User</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                Amount</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                Date</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                        @forelse($recentActivity as $activity)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $activity->wager_name }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $activity->user_name }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                    ${{ number_format($activity->amount, 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $activity->status === 'won'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                            : ($activity->status === 'lost'
                                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                                                : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                                        {{ ucfirst($activity->status) }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5"
                                                    class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                                    No recent activity found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Wagers Over Time Chart
                const wagersOverTimeCtx = document.getElementById('wagersOverTimeChart').getContext('2d');
                new Chart(wagersOverTimeCtx, {
                    type: 'line',
                    data: {
                        labels: @json(collect($wagersOverTime ?? [])->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))),
                        datasets: [{
                            label: 'Wagers Created',
                            data: @json(collect($wagersOverTime ?? [])->pluck('count')),
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // Wagers by Status Chart
                const wagersByStatusCtx = document.getElementById('wagersByStatusChart').getContext('2d');
                new Chart(wagersByStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json(collect($wagersByStatus ?? [])->pluck('status')->map(fn($status) => ucfirst($status))),
                        datasets: [{
                            data: @json(collect($wagersByStatus ?? [])->pluck('count')),
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(99, 102, 241, 0.8)'
                            ],
                            borderColor: [
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(99, 102, 241, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
