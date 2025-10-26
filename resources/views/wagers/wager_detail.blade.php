<x-app-layout>
    <div
        class="select-none min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 transition-colors">
        <div class="container mx-auto px-4 py-12 max-w-5xl">
            <div
                class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg shadow-xl rounded-2xl border border-slate-200/60 dark:border-slate-700/60 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/30 dark:hover:shadow-slate-800/20">
                <div
                    class="relative px-6 sm:px-8 lg:px-10 py-8 sm:py-10 bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-800/90 dark:via-slate-800/80 dark:to-slate-900 border-b border-slate-200/60 dark:border-slate-700/60">
                    <div class="absolute inset-0 overflow-hidden">
                        <div
                            class="absolute -inset-y-6 -inset-x-6 bg-gradient-to-r from-emerald-500/5 via-blue-500/5 to-purple-500/5 dark:from-emerald-400/8 dark:via-blue-400/8 dark:to-purple-400/8 animate-gradient">
                        </div>
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,rgba(255,255,255,0.8)_70%,transparent_100%)] dark:bg-[radial-gradient(circle_at_center,transparent_0%,rgba(15,23,42,0.9)_70%,transparent_100%)]">
                        </div>
                    </div>

                    <div class="relative max-w-7xl mx-auto">
                        <a href="{{ route('wagers.index') }}"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 duration-300">
                            <ion-icon class="size-6" name="return-up-back-outline"></ion-icon>
                        </a>
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-4 pb-4">
                                    <h1
                                        class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 dark:from-slate-100 dark:via-slate-300 dark:to-slate-100 bg-clip-text text-transparent">
                                        {{ $wager->name }}
                                    </h1>
                                    @if ($wager->status === 'ended')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Ended
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Active
                                        </span>
                                    @endif
                                </div>
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

                        <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 hover:border-slate-300/50 dark:hover:border-slate-600/50">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Ends</div>
                                <div class="mt-1 text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    {{ optional($wager->ending_time)?->diffForHumans() ?? 'N/A' }}
                                </div>
                            </div>

                            <div
                                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 hover:border-slate-300/50 dark:hover:border-slate-600/50">
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
                                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 hover:border-slate-300/50 dark:hover:border-slate-600/50">
                                <div
                                    class="text-[10px] xs:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Max Players</div>
                                <div class="mt-1 text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $wager->max_players }}
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800/40 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-emerald-200/60 dark:border-emerald-700/60 shadow-[0_4px_12px_rgba(5,150,105,0.1)] dark:shadow-[0_4px_12px_rgba(5,150,105,0.15)] transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-300/60 dark:hover:border-emerald-600/60">
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

                @if ($wager->creator_id == Auth::id() && $wager->status !== 'ended')
                    <div
                        class="px-8 py-6 border-t border-slate-200/60 dark:border-slate-700/60 bg-white/50 dark:bg-slate-900/50">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Invite People
                                </h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                    Send invitations to friends to join this wager.
                                </p>
                            </div>

                            @if ($friends->isNotEmpty())
                                <form action="{{ route('wagers.invite', $wager) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <div class="flex-1">
                                        <select name="friend_id" required
                                            class="w-full px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white/80 dark:bg-slate-800/80 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                            <option value="" disabled selected>Select a friend to invite</option>
                                            @foreach ($friends as $friend)
                                                <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        Send Invite
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    You don't have any friends to invite.
                                </p>
                            @endif
                        </div>

                        @if ($pendingInvitations->count() > 0)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pending
                                    Invitations</h4>
                                <div class="space-y-2">
                                    @foreach ($pendingInvitations as $invitation)
                                        <div
                                            class="flex items-center justify-between p-3 bg-white/50 dark:bg-slate-800/50 rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100">
                                                    {{ $invitation->email }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    Sent {{ $invitation->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Pending
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div
                    class="px-8 py-10 border-t border-slate-200/60 dark:border-slate-700/60 bg-gradient-to-b from-white/30 to-white/0 dark:from-slate-900/10 dark:to-slate-900/0">
                    <div class="flex items-center justify-between mb-8">
                        <h2
                            class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 dark:from-slate-200 dark:to-slate-400 bg-clip-text text-transparent">
                            Live Bet Distribution</h2>
                        <div
                            class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent mx-4">
                        </div>
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                    <div class="flex justify-center">
                        <div class="w-full max-w-md">
                            <canvas id="betChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>

                <div
                    class="px-8 py-10 bg-gradient-to-b from-white/50 to-white/0 dark:from-slate-900/20 dark:to-slate-900/0">
                    @if ($wager->status !== 'ended')
                        @if (!$isJoined)
                            <div
                                class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 p-6 text-center">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Join this wager to
                                    participate</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">You must join before placing
                                    a bet.</p>
                                <form method="POST" action="{{ route('wagers.join', $wager) }}"
                                    class="mt-4 inline-block">
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
                            <form method="POST" action="{{ route('wagers.bet', $wager) }}" class="space-y-4"
                                id="bet-form" onsubmit="return handleBetSubmit(event)">
                                @csrf
                                <div id="bets-container"></div>
                                <div class="space-y-4">
                                    @foreach ($wager->choices as $choice)
                                        <div
                                            class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200/50 dark:border-slate-700/50">
                                            <div class="flex items-center justify-between mb-3">
                                                <span
                                                    class="font-medium text-slate-900 dark:text-white">{{ $choice->label }}</span>
                                                <span
                                                    class="text-sm text-slate-500 dark:text-slate-400">{{ number_format($choice->total_bet, 0) }}
                                                    bet</span>
                                            </div>
                                            <div class="relative">
                                                <input type="number" data-choice-id="{{ $choice->id }}"
                                                    placeholder="Enter amount"
                                                    class="bet-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white/50 dark:bg-slate-800/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    min="1" step="1">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex items-center gap-3 mt-6">
                                    <button type="submit" id="submit-btn"
                                        class="flex-1 py-3 px-4 rounded-lg text-white font-medium transition-all duration-200 bg-emerald-600 hover:bg-emerald-700">
                                        <span id="submit-text">Place Bet</span>
                                        <span id="submit-spinner" class="hidden ml-2">
                                            <svg class="animate-spin h-5 w-5 text-white inline"
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
                            </form>

                            @if ($wager->creator_id == Auth::id())
                                <div class="mt-8 pt-6 border-t border-slate-200/60 dark:border-slate-700/60">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <a href="{{ route('wagers.edit', $wager) }}" class="flex-1">
                                            <button type="button"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200
                                                bg-white/80 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800
                                                border border-slate-200/80 hover:border-emerald-300 dark:border-slate-700/80 dark:hover:border-emerald-500/60
                                                text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300
                                                shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                                <ion-icon class="text-base" name="create-outline"></ion-icon>
                                                <span>Edit Wager</span>
                                            </button>
                                        </a>

                                        <button type="button" id="endWagerButton"
                                            class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200
                                            bg-white/80 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800
                                            border border-slate-200/80 hover:border-blue-300 dark:border-slate-700/80 dark:hover:border-blue-500/60
                                            text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300
                                            shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                            <ion-icon class="text-base" name="flag-outline"></ion-icon>
                                            <span>End Wager</span>
                                        </button>
                                    </div>
                                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 text-center">Only visible
                                        to you • Creator controls</p>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include the End Wager Modal -->
    @include('wagers.wagers_end')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let betChart = null;
        let initialData = [];
        let pollInterval = null;

        const chartColors = [
            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
        ];

        @php
            $chartData = $wager->choices
                ->map(function ($choice) {
                    return [
                        'id' => $choice->id,
                        'label' => $choice->label,
                        'total_bet' => (float) $choice->total_bet,
                    ];
                })
                ->toArray();
        @endphp
        initialData = @json($chartData);

        function renderChart(data) {
            const canvas = document.getElementById('betChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            let labels = data.map(d => d.label || 'Unknown');
            let values = data.map(d => parseFloat(d.total_bet) || 0);
            const totalBets = values.reduce((a, b) => a + b, 0);

            if (totalBets === 0) {
                labels = ['No bets yet'];
                values = [1];
            }

            if (betChart) betChart.destroy();

            const isDark = document.documentElement.classList.contains('dark');
            const backgroundColor = totalBets === 0 ? ['#E5E7EB'] : chartColors.slice(0, values.length);
            const borderColor = isDark ? '#1e293b' : '#ffffff';

            betChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: backgroundColor,
                        borderWidth: 3,
                        borderColor: borderColor,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: isDark ? '#e2e8f0' : '#334155',
                                padding: 15,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    if (totalBets === 0) return 'No bets yet';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: ${value.toLocaleString()} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateChartWithUserBets() {
            const inputs = document.querySelectorAll('.bet-input');
            const userBets = {};

            inputs.forEach(input => {
                const amount = parseFloat(input.value) || 0;
                const choiceId = parseInt(input.dataset.choiceId);
                userBets[choiceId] = amount;
            });

            const chartData = initialData.map(choice => ({
                id: choice.id,
                label: choice.label,
                total_bet: (parseFloat(choice.total_bet) || 0) + (userBets[choice.id] || 0)
            }));

            renderChart(chartData);
        }

        async function fetchWagerStats() {
            try {
                const response = await fetch(`/wagers/{{ $wager->id }}/stats`);
                if (!response.ok) throw new Error('Fetch failed');
                return await response.json();
            } catch (error) {
                console.error('Error fetching stats:', error);
                return null;
            }
        }

        function updateUIWithServerData(data) {
            if (!data) return;

            const potDisplay = document.getElementById('pot-display');
            if (potDisplay && data.pot !== undefined) potDisplay.textContent = Math.round(data.pot).toLocaleString();

            if (data.distribution && Array.isArray(data.distribution)) {
                initialData = data.distribution.map(item => ({
                    id: item.id,
                    label: item.label,
                    total_bet: parseFloat(item.amount) || 0
                }));
                renderChart(initialData);
            }
        }

        async function handleBetSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('#submit-btn');
            const submitText = form.querySelector('#submit-text');
            const submitSpinner = form.querySelector('#submit-spinner');
            const betsContainer = form.querySelector('#bets-container');

            submitBtn.disabled = true;
            submitText.textContent = 'Validating...';
            submitSpinner.classList.remove('hidden');

            betsContainer.innerHTML = '';

            const bets = [];
            let totalBetAmount = 0;

            document.querySelectorAll('.bet-input').forEach(input => {
                const amount = parseFloat(input.value) || 0;
                if (amount > 0) {
                    const betInput = document.createElement('input');
                    betInput.type = 'hidden';
                    betInput.name = `bets[${bets.length}][choice_id]`;
                    betInput.value = input.dataset.choiceId;
                    betsContainer.appendChild(betInput);

                    const amountInput = document.createElement('input');
                    amountInput.type = 'hidden';
                    amountInput.name = `bets[${bets.length}][amount]`;
                    amountInput.value = amount;
                    betsContainer.appendChild(amountInput);

                    bets.push({
                        choice_id: input.dataset.choiceId,
                        amount: amount
                    });
                    totalBetAmount += amount;
                    input.value = '';
                }
            });

            if (bets.length === 0 || totalBetAmount <= 0) {
                submitBtn.disabled = false;
                submitText.textContent = 'Place Bet';
                submitSpinner.classList.add('hidden');
                return;
            }

            submitText.textContent = 'Placing Bets...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to place bets');
                }

                const successDiv = document.createElement('div');
                successDiv.className =
                    'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                successDiv.textContent = 'Bets placed successfully!';
                document.body.appendChild(successDiv);
                setTimeout(() => successDiv.remove(), 3000);

                // Update chart
                const statsData = await fetchWagerStats();
                if (statsData) updateUIWithServerData(statsData);

            } catch (error) {
                console.error('Error placing bets:', error);
                alert(error.message || 'An error occurred while placing your bets');
            } finally {
                submitBtn.disabled = false;
                submitText.textContent = 'Place Bet';
                submitSpinner.classList.add('hidden');
            }
        }

        function startPolling() {
            if (pollInterval) return;
            pollInterval = setInterval(async () => {
                const data = await fetchWagerStats();
                if (data) updateUIWithServerData(data);
            }, 10000);
        }

        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderChart(initialData);

            const inputs = document.querySelectorAll('.bet-input');
            inputs.forEach(input => {
                input.addEventListener('input', updateChartWithUserBets);
                input.addEventListener('change', updateChartWithUserBets);
            });

            const form = document.getElementById('bet-form');
            if (form) form.addEventListener('submit', handleBetSubmit);

            startPolling();

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stopPolling();
                else startPolling();
            });

            window.addEventListener('beforeunload', stopPolling);
        });
    </script>
</x-app-layout>
