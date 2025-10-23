<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                        Analytics Dashboard
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Real-time wager platform statistics</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <!-- Total Wagers -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">
                            Total Wagers
                        </p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ number_format($stats['total_wagers']) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            <span
                                class="text-amber-600 dark:text-amber-400 font-medium">{{ $stats['pending_wagers'] }}</span>
                            pending
                        </p>
                    </div>

                    <!-- Active Wagers -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">
                            Active Wagers
                        </p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ number_format($stats['active_wagers']) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            <span
                                class="text-orange-600 dark:text-orange-400 font-medium">{{ $metrics['active_wagers_ending_soon'] }}</span>
                            ending soon
                        </p>
                    </div>

                    <!-- Total Wagered -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">
                            Total Wagered
                        </p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            ${{ number_format($stats['total_wagered'], 2) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            <span
                                class="text-purple-600 dark:text-purple-400 font-medium">${{ number_format($metrics['total_wagered_this_week'], 2) }}</span>
                            this week
                        </p>
                    </div>

                    <!-- Total Users -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">
                            Total Users
                        </p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ number_format($stats['total_users']) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            <span
                                class="text-cyan-600 dark:text-cyan-400 font-medium">{{ $metrics['new_users_this_week'] }}</span>
                            new this week
                        </p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                    <!-- Wagers Over Time -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Wager Trends (14 Days)
                        </h3>
                        <div class="relative h-64">
                            <canvas id="wagersOverTimeChart"></canvas>
                        </div>
                    </div>

                    <!-- Wagers by Status -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Status Distribution</h3>
                        <div class="relative h-64">
                            <canvas id="wagersByStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                    <!-- Top Wagers -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Top Wagers by Pot</h3>
                        <div class="space-y-3">
                            @forelse($topWagers as $wager)
                                <div
                                    class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                            {{ $wager->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $wager->player_count }} players
                                        </p>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            ${{ number_format($wager->total_amount, 2) }}
                                        </p>
                                        @php
                                            $statusClasses = [
                                                'active' =>
                                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'ended' =>
                                                    'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400',
                                                'pending' =>
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            ];
                                            $statusClass =
                                                $statusClasses[$wager->status] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }} mt-1">
                                            {{ ucfirst($wager->status) }}
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
                        class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Key Metrics</h3>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Average Pot Size</p>
                                </div>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">
                                    ${{ number_format($stats['avg_pot'], 2) }}
                                </p>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Completion Rate</p>
                                </div>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">
                                    {{ $stats['total_wagers'] > 0 ? number_format(($stats['completed_wagers'] / $stats['total_wagers']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Recent Payouts</p>
                                </div>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">
                                    ${{ number_format($metrics['recent_payouts'], 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Wager
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($recentActivity as $activity)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                                    {{ substr($activity->user_name, 0, 1) }}
                                                </div>
                                                <span class="ml-3 text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $activity->user_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                {{ Str::limit($activity->wager_name, 30) }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                Pot: ${{ number_format($activity->wager_pot, 2) }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-white">
                                            ${{ number_format($activity->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'won' =>
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                    'lost' =>
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                    'pending' =>
                                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                                ];
                                                $statusClass =
                                                    $statusClasses[$activity->status] ?? 'bg-slate-100 text-slate-700';
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
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
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mb-3"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-slate-500 dark:text-slate-400">No recent
                                                    activity found</p>
                                            </div>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#94a3b8' : '#64748b';
                const gridColor = isDark ? '#1e293b' : '#e2e8f0';

                // Wagers Over Time Chart
                const wagersOverTimeData = @json($wagersOverTime);

                const ctx1 = document.getElementById('wagersOverTimeChart');
                if (ctx1 && wagersOverTimeData.length > 0) {
                    new Chart(ctx1.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: wagersOverTimeData.map(item => {
                                const date = new Date(item.date);
                                return date.toLocaleDateString('en-US', {
                                    month: 'short',
                                    day: 'numeric'
                                });
                            }),
                            datasets: [{
                                label: 'Wagers Created',
                                data: wagersOverTimeData.map(item => item.count),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                    titleColor: isDark ? '#ffffff' : '#0f172a',
                                    bodyColor: isDark ? '#ffffff' : '#0f172a',
                                    borderColor: isDark ? '#334155' : '#e2e8f0',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        color: textColor,
                                        font: {
                                            size: 11
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
                                            size: 11
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

                // Wagers by Status Chart
                const wagersByStatusData = @json($wagersByStatus);

                const ctx2 = document.getElementById('wagersByStatusChart');
                if (ctx2 && wagersByStatusData.length > 0) {
                    const statusColors = {
                        'active': '#10b981',
                        'ended': '#64748b',
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
                                data: wagersByStatusData.map(item => item.count),
                                backgroundColor: wagersByStatusData.map(item => {
                                    const status = (item.status || '').toLowerCase();
                                    return statusColors[status] || '#9ca3af';
                                }),
                                borderWidth: 3,
                                borderColor: isDark ? '#0f172a' : '#ffffff',
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: textColor,
                                        font: {
                                            size: 12
                                        },
                                        padding: 15,
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
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((value / total) * 100).toFixed(
                                                1) : 0;
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
