<x-app-layout>
    <div
        class="select-none min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 transition-colors">
        <div class="container mx-auto px-4 py-12 max-w-5xl">

            @if (session('success'))
                <div
                    class="mb-8 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 text-emerald-800 px-6 py-4 shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-8 rounded-xl bg-gradient-to-r from-rose-50 to-rose-100 border border-rose-200 text-rose-800 px-6 py-4 shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-rose-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div
                class="bg-white/90 dark:bg-slate-900/60 backdrop-blur-md shadow-2xl rounded-2xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">

                <div
                    class="relative px-4 sm:px-6 lg:px-8 py-6 sm:py-8 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 border-b border-slate-200/50 dark:border-slate-700/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-blue-500/5 dark:from-emerald-400/5 dark:to-blue-400/5">
                    </div>

                    <div class="relative max-w-7xl mx-auto">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <h1
                                    class="pb-4 text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 dark:from-slate-100 dark:via-slate-300 dark:to-slate-100 bg-clip-text text-transparent">
                                    {{ $wager->name }}
                                </h1>
                                <p
                                    class="mt-2 sm:mt-3 text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-3xl leading-relaxed">
                                    {{ $wager->description }}
                                </p>
                            </div>

                            <div class="flex-shrink-0 mt-2 sm:mt-0">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 sm:py-2 rounded-full text-xs sm:text-sm font-medium bg-white/80 dark:bg-slate-800/70 backdrop-blur-sm border border-slate-200/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 shadow-sm">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                    </svg>
                                    <span>Created by: <strong
                                            class="font-semibold text-slate-800 dark:text-slate-100">{{ optional($wager->creator)->name ?? 'Unknown' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-3 sm:p-4 border border-slate-200/50 dark:border-slate-700/50 transition-all hover:shadow-sm">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Ends</div>
                                <div class="mt-1 text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    {{ optional($wager->ending_time)?->diffForHumans() ?? 'N/A' }}
                                </div>
                            </div>

                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-3 sm:p-4 border border-slate-200/50 dark:border-slate-700/50 transition-all hover:shadow-sm">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Status</div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span
                                        class="w-2 h-2 rounded-full flex-shrink-0 {{ $wager->status === 'public' ? 'bg-emerald-500 shadow-lg shadow-emerald-500/30' : 'bg-slate-400' }}"></span>
                                    <span
                                        class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100 truncate">
                                        {{ ucfirst($wager->status) }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-3 sm:p-4 border border-slate-200/50 dark:border-slate-700/50 transition-all hover:shadow-sm">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Max Players</div>
                                <div class="mt-1 text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $wager->max_players }}
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 backdrop-blur-sm rounded-lg p-3 sm:p-4 border border-emerald-200/50 dark:border-emerald-700/50 transition-all hover:shadow-sm">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">
                                    Total Pot</div>
                                <div class="mt-1 text-base sm:text-lg font-bold text-emerald-800 dark:text-emerald-200"
                                    id="pot-display">
                                    {{ number_format($wager->pot, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-8 border-t border-slate-200/50 dark:border-slate-700/50">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Live Bet Distribution</h2>
                    <div class="flex justify-center">
                        <div class="w-full max-w-md">
                            <canvas id="betChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-8">
                    @if (!$isJoined)
                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 p-6 text-center">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Join this wager to
                                participate</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">You can view details, but you
                                must join before placing a bet.</p>
                            <form method="POST" action="{{ route('wagers.join', $wager) }}" class="mt-4 inline-block">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium shadow-sm transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Join Wager
                                </button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('wagers.bet', $wager) }}" class="space-y-8"
                            id="bet-form">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @if ($userBet && $userBet->choice_id)
                                <div
                                    class="rounded-xl border border-blue-200 dark:border-blue-900 bg-blue-50/50 dark:bg-blue-900/20 p-6">
                                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Current Bet</h3>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                        You've bet {{ number_format($userBet->bet_amount, 0) }} on this wager. You can
                                        change your bet below.
                                    </p>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($wager->choices as $choice)
                                        <div
                                            class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200/50 dark:border-slate-700/50">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-medium text-slate-900 dark:text-slate-100">
                                                    {{ $choice->label }}</h3>
                                            </div>

                                            <div class="space-y-2">
                                                <input type="number" name="bets[{{ $loop->index }}][amount]"
                                                    id="bet_{{ $choice->id }}"
                                                    class="bet-input block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                                    placeholder="Bet" min="0" step="1">
                                                <input type="hidden" name="bets[{{ $loop->index }}][choice_id]"
                                                    value="{{ $choice->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit" id="submit-btn"
                                        class="flex-1 py-3 px-4 rounded-lg text-white font-medium transition-all duration-200 bg-emerald-600 hover:bg-emerald-700">
                                        <span id="submit-text">Place Bet</span>
                                        <span id="submit-spinner" class="hidden ml-2">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables
        let betChart = null;
        let userBalance = {{ auth()->user()->balance }};

        @php
            $chartData = $wager->choices
                ->map(function ($choice) {
                    return [
                        'id' => $choice->id,
                        'label' => $choice->label,
                        'total_bet' => $choice->total_bet,
                    ];
                })
                ->toArray();
        @endphp

        let initialData = @json($chartData);

        // Create or update the pie chart
        function renderChart(data) {
            const canvas = document.getElementById('betChart');
            if (!canvas) return;

            // Prepare chart data
            let labels = data.map(d => d.label);
            let values = data.map(d => parseFloat(d.total_bet) || 0);

            const totalBets = values.reduce((a, b) => a + b, 0);

            // Show placeholder if no bets
            if (totalBets === 0) {
                labels = ['No bets yet'];
                values = [1];
            }

            // Destroy old chart
            if (betChart) {
                betChart.destroy();
            }

            // Create new chart
            betChart = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: totalBets === 0 ? ['#E5E7EB'] : [
                            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                        ],
                        borderWidth: 2,
                        borderColor: ''
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    if (totalBets === 0) return 'No bets yet';
                                    const value = ctx.raw || 0;
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = Math.round((value / total) * 100);
                                    return `${ctx.label}: ${value} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update chart when user types in bet inputs
        function updateChartWithUserBets() {
            const inputs = document.querySelectorAll('.bet-input');
            const userBets = {};

            // Collect user's current bet inputs
            inputs.forEach(input => {
                const amount = parseFloat(input.value) || 0;
                const hiddenInput = input.parentElement.querySelector('input[name*="[choice_id]"]');
                if (hiddenInput) {
                    const choiceId = parseInt(hiddenInput.value);
                    userBets[choiceId] = amount;
                }
            });

            // Combine initial data with user's current bets
            const chartData = initialData.map(choice => ({
                id: choice.id,
                label: choice.label,
                total_bet: (parseFloat(choice.total_bet) || 0) + (userBets[choice.id] || 0)
            }));

            renderChart(chartData);
        }

        // Fetch latest wager data from server
        async function fetchWagerStats() {
            try {
                const response = await fetch(`/wagers/{{ $wager->id }}/stats`);
                if (!response.ok) throw new Error('Failed to fetch');
                return await response.json();
            } catch (error) {
                console.error('Error fetching wager stats:', error);
                return null;
            }
        }

        // Update UI with fresh data from server
        function updateUIWithServerData(data) {
            if (!data) return;

            // Update pot
            document.getElementById('pot-display').textContent = Math.round(data.pot).toLocaleString();

            // Update chart data
            initialData = data.distribution.map(item => ({
                id: item.id,
                label: item.label,
                total_bet: parseFloat(item.amount) || 0
            }));

            renderChart(initialData);
        }

        // Handle bet form submission
        async function handleBetSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Placing Bet...';
            submitSpinner.classList.remove('hidden');

            try {
                const formData = new FormData(form);
                const token = form.querySelector('input[name="_token"]').value;

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                });

                const contentType = response.headers.get('content-type') || '';
                let result = null;

                if (contentType.includes('application/json')) {
                    result = await response.json();
                }

                if (response.ok && result && result.success) {
                    // Update UI with new data
                    if (result.wager) {
                        document.getElementById('pot-display').textContent = Math.round(result.wager.pot)
                            .toLocaleString();

                        initialData = result.wager.choices.map(choice => ({
                            id: choice.id,
                            label: choice.label,
                            total_bet: parseFloat(choice.total_bet) || 0
                        }));

                        renderChart(initialData);

                        // Update balance if provided
                        if (result.wager.user_balance !== undefined) {
                            const balanceEl = document.querySelector('.balance-amount');
                            if (balanceEl) {
                                balanceEl.textContent = parseInt(result.wager.user_balance).toLocaleString();
                            }
                        }
                    }

                    form.reset();
                    updateChartWithUserBets();
                } else if (response.ok) {
                    // Server returned HTML instead of JSON, fetch fresh data
                    const freshData = await fetchWagerStats();
                    if (freshData) {
                        updateUIWithServerData(freshData);
                        form.reset();
                        updateChartWithUserBets();
                    } else {
                        window.location.reload();
                    }
                } else {
                    throw new Error(result?.message || 'Failed to place bet');
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Place Bet';
                submitSpinner.classList.add('hidden');
            }
        }

        // Polling function to keep chart updated
        let pollInterval = null;

        function startPolling() {
            pollInterval = setInterval(async () => {
                const data = await fetchWagerStats();
                if (data) {
                    document.getElementById('pot-display').textContent = Math.round(data.pot).toLocaleString();
                    initialData = data.distribution.map(item => ({
                        id: item.id,
                        label: item.label,
                        total_bet: parseFloat(item.amount) || 0
                    }));
                    renderChart(initialData);
                }
            }, 15000); // Poll every 15 seconds instead of 5
        }

        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        // Initialize everything when page loads
        window.addEventListener('load', function() {
            // Wait a moment for Chart.js to be ready
            setTimeout(() => {
                // Render initial chart
                renderChart(initialData);

                // Set up bet input listeners
                const inputs = document.querySelectorAll('.bet-input');
                inputs.forEach(input => {
                    input.addEventListener('input', updateChartWithUserBets);
                    input.addEventListener('change', updateChartWithUserBets);
                });

                // Set up form submission
                const form = document.getElementById('bet-form');
                if (form) {
                    form.addEventListener('submit', handleBetSubmit);
                }

                // Start polling for updates
                startPolling();

                // Handle page visibility changes
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        stopPolling();
                    } else {
                        startPolling();
                    }
                });

                // Clean up on page unload
                window.addEventListener('beforeunload', stopPolling);
            }, 500);
        });
    </script>
</x-app-layout>
