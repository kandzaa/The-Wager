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
                                        @error('friend_id')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
                                        Send Invite
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    You don't have any friends to invite or all friends are already
                                    invited/participating.
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
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">You can view details, but you
                                    must join before placing a bet.</p>
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
                                @method('POST')
                                <div id="bets-container"></div> <!-- Container for hidden bets inputs -->
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
                                        <a href="{{ route('wagers.edit', $wager) }}" class="flex-1 group">
                                            <button type="button"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200
                                                bg-white/80 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800
                                                border border-slate-200/80 hover:border-emerald-300 dark:border-slate-700/80 dark:hover:border-emerald-500/60
                                                text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300
                                                shadow-sm hover:shadow-md hover:-translate-y-0.5
                                                focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                                                <ion-icon class="text-base" name="create-outline"></ion-icon>
                                                <span>Edit Wager</span>
                                            </button>
                                        </a>

                                        <form action="{{ route('wagers.destroy', $wager) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return deleteWager({{ $wager->id }})"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200
                                                bg-white/80 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800
                                                border border-slate-200/80 hover:border-rose-300 dark:border-slate-700/80 dark:hover:border-rose-500/60
                                                text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300
                                                shadow-sm hover:shadow-md hover:-translate-y-0.5
                                                focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                                                <ion-icon class="text-base" name="trash-outline"></ion-icon>
                                                <span>Delete Wager</span>
                                            </button>
                                        </form>

                                        <button type="button" id="endWagerButton"
                                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200
                                            bg-white/80 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800
                                            border border-slate-200/80 hover:border-gray-300 dark:border-slate-700/80 dark:hover:border-gray-500/60
                                            text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300
                                            shadow-sm hover:shadow-md hover:-translate-y-0.5
                                            focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
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

        <!-- End Wager Modal -->
        <div id="endWagerModal"
            class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden"
            x-data="{ selectedChoiceId: null, confirming: false }">
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 max-w-3xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">End Wager: {{ $wager->name }}</h1>
                <form method="POST" action="{{ route('wagers.end', $wager) }}" id="endWagerForm"
                    @submit.prevent="handleEndWagerSubmit">
                    @csrf
                    <input type="hidden" name="winning_choice_id" x-model="selectedChoiceId">
                    <div x-show="!confirming">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Select the winning choice:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($wager->choices as $choice)
                                <button type="button"
                                    @click="selectedChoiceId = {{ $choice->id }}; confirming = true;"
                                    class="w-full text-left p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700/80 shadow-sm transition"
                                    data-choice-id="{{ $choice->id }}">
                                    <span class="font-medium">{{ $choice->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div x-show="confirming" x-cloak>
                        <p class="text-sm text-slate-700 dark:text-slate-200 mb-4">
                            You've selected:
                            <span class="font-medium"
                                x-text="selectedChoiceId ? document.querySelector(`button[data-choice-id='${selectedChoiceId}']`).textContent.trim() : ''"></span>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                            Are you sure you want to end this wager and select this as the winning choice? This action
                            cannot be undone.
                        </p>
                        <div class="flex items-center space-x-3">
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition"
                                x-bind:disabled="!selectedChoiceId">
                                Confirm and End Wager
                            </button>
                            <button type="button" @click="confirming = false; selectedChoiceId = null;"
                                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg font-medium transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables
        let betChart = null;
        let initialData = [];
        let pollInterval = null;

        // Chart colors
        const chartColors = [
            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
        ];

        // Initialize data from PHP
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
        console.log('Initial data loaded:', initialData);

        // Render chart
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
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: isDark ? '#1e293b' : '#ffffff',
                            titleColor: isDark ? '#e2e8f0' : '#334155',
                            bodyColor: isDark ? '#cbd5e1' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
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
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    }
                }
            });
        }

        // Update chart with user's bet inputs
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

        // Fetch stats from server
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

        // Update UI with server data
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

        // Handle bet form submission
        async function handleBetSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('#submit-btn');
            const submitText = form.querySelector('#submit-text');
            const submitSpinner = form.querySelector('#submit-spinner');
            const betsContainer = form.querySelector('#bets-container');

            if (!submitBtn || !submitText || !submitSpinner || !betsContainer) {
                console.error('Missing form elements');
                return;
            }

            submitBtn.disabled = true;
            submitText.textContent = 'Validating...';
            submitSpinner.classList.remove('hidden');

            // Clear previous bets
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
                    input.value = ''; // Clear input after capturing
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
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to place bets');
                }

                if (result.distribution) {
                    initialData = result.distribution.map(item => ({
                        id: item.id,
                        label: item.label,
                        total_bet: parseFloat(item.amount) || 0
                    }));
                    renderChart(initialData);
                }

                if (result.wager?.user_balance !== undefined) {
                    const balanceEl = document.querySelector('.balance-amount');
                    if (balanceEl) balanceEl.textContent = parseInt(result.wager.user_balance).toLocaleString();
                }

                const successDiv = document.createElement('div');
                successDiv.className =
                    'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                successDiv.textContent = 'Bets placed successfully!';
                document.body.appendChild(successDiv);
                setTimeout(() => successDiv.remove(), 3000);

                updateChartWithUserBets();

            } catch (error) {
                console.error('Error placing bets:', error);
                let errorMessage = error.message || 'An error occurred while placing your bets';
                if (error.errors && error.errors.amount) errorMessage = error.errors.amount[0];
                alert(errorMessage);
            } finally {
                submitBtn.disabled = false;
                submitText.textContent = 'Place Bet';
                submitSpinner.classList.add('hidden');
            }
        }

        // Start polling
        function startPolling() {
            if (pollInterval) return;
            pollInterval = setInterval(async () => {
                const data = await fetchWagerStats();
                if (data) updateUIWithServerData(data);
            }, 10000);
        }

        // Stop polling
        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        // Modal functions
        function openEndWagerModal() {
            const modal = document.getElementById('endWagerModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const firstInput = modal.querySelector('input[type="radio"]');
                if (firstInput) firstInput.focus();
            }
        }

        function closeEndWagerModal() {
            const modal = document.getElementById('endWagerModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                const form = document.getElementById('endWagerForm');
                if (form) form.reset();
                // Reset Alpine.js state
                Alpine.$data(modal).selectedChoiceId = null;
                Alpine.$data(modal).confirming = false;
            }
        }

        // Handle end wager form submission
        async function handleEndWagerSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML =
                `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...`;

            const formData = new FormData(form);
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');
            headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            headers.append('Accept', 'application/json');
            headers.append('Content-Type', 'application/json');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({
                        winning_choice_id: formData.get('winning_choice_id')
                    }),
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Failed to end wager');
                }
            } catch (error) {
                console.error('Error:', error);
                let errorMessage = error.message || 'An error occurred';
                if (error.errors) errorMessage = Object.values(error.errors).flat().join(' ');

                const errorDiv = document.createElement('div');
                errorDiv.className =
                    'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-lg p-4';
                errorDiv.innerHTML =
                    `<div class="flex items-start gap-3"><svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg><div><h4 class="text-sm font-medium text-red-800 dark:text-red-200">There was an error ending the wager:</h4><p class="text-sm text-red-700 dark:text-red-300">${errorMessage}</p></div></div>`;
                form.insertBefore(errorDiv, form.firstChild);
                errorDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }

        // Initialize on page load
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

            const endWagerButton = document.getElementById('endWagerButton');
            if (endWagerButton) {
                endWagerButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    openEndWagerModal();
                });
            }

            const modal = document.getElementById('endWagerModal');
            window.addEventListener('click', function(e) {
                if (e.target === modal) closeEndWagerModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeEndWagerModal();
            });

            // Show modal if session indicates
            @if (session('show_end_modal'))
                openEndWagerModal();
            @endif
        });
    </script>
</x-app-layout>
