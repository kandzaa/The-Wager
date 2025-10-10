<x-app-layout>
    <div class="min-h-screen bg-white dark:bg-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white mb-1">
                        Analytics Dashboard
                    </h1>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                    <!-- Total Wagers -->
                    <div class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">
                            Total Wagers
                        </p>
                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ number_format($stats['total_wagers'] ?? 0) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            {{ $stats['pending_wagers'] ?? 0 }} pending
                        </p>
                    </div>

                    <!-- Active Wagers -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">
                            Active Wagers
                        </p>
                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ number_format($stats['active_wagers'] ?? 0) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            {{ $metrics['active_wagers_ending_soon'] ?? 0 }} ending soon
                        </p>
                    </div>

                    <!-- Total Wagered -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">
                            Total Wagered
                        </p>
                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                            ${{ number_format($stats['total_wagered'] ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            ${{ number_format($metrics['total_wagered_this_week'] ?? 0, 2) }} this week
                        </p>
                    </div>

                    <!-- Total Users -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">
                            Total Users
                        </p>
                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ number_format($stats['total_users'] ?? 0) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            {{ $metrics['new_users_this_week'] ?? 0 }} new this week
                        </p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

                    <!-- Wagers Over Time -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-4">Wager Trends</h3>
                        <div class="relative h-64">
                            <canvas id="wagersOverTimeChart"></canvas>
                        </div>
                    </div>

                    <!-- Wagers by Status -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-4">Status Distribution</h3>
                        <div class="relative h-64">
                            <canvas id="wagersByStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

                    <!-- Top Wagers -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-4">Top Wagers by Pot</h3>
                        <div class="space-y-2">
                            @forelse($topWagers ?? [] as $wager)
                                <div
                                    class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                            {{ $wager->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $wager->player_count ?? 0 }} players
                                        </p>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            ${{ number_format($wager->total_amount ?? 0, 2) }}
                                        </p>
                                        @php
                                            $statusClasses = [
                                                'active' =>
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                'completed' =>
                                                    'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400',
                                                'pending' =>
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            ];
                                            $statusClass =
                                                $statusClasses[strtolower($wager->status ?? '')] ??
                                                'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }} mt-1">
                                            {{ ucfirst($wager->status ?? 'Unknown') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-sm text-slate-500 dark:text-slate-400 py-8">
                                    No wagers available
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-4">Key Metrics</h3>
                        <div class="space-y-3">
                            <div
                                class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-sm text-slate-600 dark:text-slate-400">Average Pot Size</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    ${{ number_format($stats['avg_pot'] ?? 0, 2) }}
                                </p>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-sm text-slate-600 dark:text-slate-400">Completion Rate</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ ($stats['total_wagers'] ?? 0) > 0 ? number_format((($stats['completed_wagers'] ?? 0) / $stats['total_wagers']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <p class="text-sm text-slate-600 dark:text-slate-400">Recent Payouts</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    ${{ number_format($metrics['recent_payouts'] ?? 0, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Recent Activity Table -->
                <div
                    class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                        User
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                        Wager
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                        Amount
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                        Status
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">
                                        Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($recentActivity ?? [] as $activity)
                                    <tr>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if (!empty($activity->user_avatar))
                                                    <img class="w-8 h-8 rounded-full"
                                                        src="{{ $activity->user_avatar }}" alt="">
                                                @else
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 text-sm font-medium">
                                                        {{ substr($activity->user_name ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                                <span class="ml-3 text-sm text-slate-900 dark:text-white">
                                                    {{ $activity->user_name ?? 'Unknown' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-900 dark:text-white">
                                                {{ $activity->wager_name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                Pot: ${{ number_format($activity->wager_pot ?? 0, 2) }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-5 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                            ${{ number_format($activity->amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'won' =>
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                    'lost' =>
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                    'pending' =>
                                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                                    'active' =>
                                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                    'completed' =>
                                                        'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400',
                                                ];
                                                $statusClass =
                                                    $statusClasses[strtolower($activity->status ?? '')] ??
                                                    'bg-slate-100 text-slate-700';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClass }}">
                                                {{ ucfirst($activity->status ?? 'Unknown') }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-5 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                            {{ $activity->created_at->diffForHumans() ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
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

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                        const isDark = document.documentElement.classList.contains('dark');
                        const textColor = isDark ? '#94a3b8' : '#64748b';
                        const gridColor = isDark ? '#1e293b' : '#e2e8f0';

                        // Common chart options
                        const chartOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: textColor,
                                        font: {
                                            size: 11
                                        },
                                        padding: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                    titleColor: isDark ? '#ffffff' : '#0f172a',
                                    bodyColor: isDark ? '#ffffff' : '#0f172a',
                                    borderColor: isDark ? '#334155' : '#e2e8f0',
                                    borderWidth: 1,
                                    padding: 10,
                                    displayColors: true,
                                    boxPadding: 4
                                }
                            }
                        };

                        // Wagers Over Time Chart
                        const wagersOverTimeData = {!! json_encode($wagersOverTime ?? []) !!};

                        if (wagersOverTimeData && wagersOverTimeData.length > 0) {
                            const ctx1 = document.getElementById('wagersOverTimeChart');
                            if (ctx1) {
                                new Chart(ctx1.getContext('2d'), {
                                    type: 'line',
                                    data: {
                                        labels: wagersOverTimeData.map(item => {
                                            if (!item.date) return '';
                                            const date = new Date(item.date);
                                            return date.toLocaleDateString('en-US', {
                                                month: 'short',
                                                day: 'numeric'
                                            });
                                        }),
                                        datasets: [{
                                            label: 'Wagers',
                                            data: wagersOverTimeData.map(item => parseInt(item.count) || 0),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.3,
                                            pointRadius: 3,
                                            pointHoverRadius: 5,
                                            pointBackgroundColor: '#3b82f6',
                                            pointBorderColor: '#fff',
                                            pointBorderWidth: 2
                                        }]
                                    },
                                    options: {
                                        ...chartOptions,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    precision: 0,
                                                    color: textColor,
                                                    font: {
                                                        size: 10
                                                    }
                                                },
                                                grid: {
                                                    color: gridColor,
                                                    drawBorder: false
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    color: textColor,
                                                    font: {
                                                        size: 10
                                                    }
                                                },
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }

                        // Wagers by Status Chart
                        const wagersByStatusData = {!! json_encode($wagersByStatus ?? []) !!};

                        if (wagersByStatusData && wagersByStatusData.length > 0) {
                            const ctx2 = document.getElementById('wagersByStatusChart');
                            if (ctx2) {
                                const statusColors = {
                                    'active': '#3b82f6',
                                    'completed': '#64748b',
                                    'pending': '#f59e0b',
                                    'cancelled': '#ef4444'
                                };

                                new Chart(ctx2.getContext('2d'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: wagersByStatusData.map(item => {
                                            const status = item.status || 'Unknown';
                                            return status.charAt(0).toUpperCase() + status.slice(1);
                                        }),
                                        datasets: [{
                                            data: wagersByStatusData.map(item => parseInt(item.count) || 0),
                                            backgroundColor: wagersByStatusData.map(item => {
                                                const status = (item.status || '').toLowerCase();
                                                return statusColors[status] || '#9ca3af';
                                            }),
                                            borderWidth: 2,
                                            borderColor: isDark ? '#0f172a' : '#ffffff',
                                            hoverOffset: 6
                                        }]
                                    },
                                    options: {
                                        ...chartOptions,
                                        cutout: '65%',
                                        plugins: {
                                            ...chartOptions.plugins,
                                            legend: {
                                                ...chartOptions.plugins.legend,
                                                position: 'right'
                                            },
                                            tooltip: {
                                                ...chartOptions.plugins.tooltip,
                                                callbacks: {
                                                    label: function(context) {
                                                        const label = context.label || '';
                                                        const value = context.parsed || 0;
                                                        const total = context.dataset.data.reduce((a, b) => a + b,
                                                            0);
                                                        const percentage = total > 0 ? ((value / total) * 100)
                                                            .toFixed(1) : 0;
                                                        return `${label}: ${value} (${percentage}%)`;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if (window.betChart) {
                                        window.betChart.destroy();
                                    }
                                });
                            });
        </script>
    @endpush
</x-app-layout>
