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
                        <a href="{{ route('wagers') }}"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 duration-300">
                            <ion-icon class="size-6" name="return-up-back-outline"></ion-icon>
                        </a>
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-4 pb-4">
                                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 dark:from-slate-100 dark:via-slate-300 dark:to-slate-100 bg-clip-text text-transparent">
                                        {{ $wager->name }}
                                    </h1>
                                    @if($wager->status === 'ended')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Ended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
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
                    @if ($wager->status === 'ended')
                        <div
                            class="rounded-2xl bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/60 dark:border-slate-700/60 p-6 space-y-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Results</h3>
                                <p class="text-slate-600 dark:text-slate-300 mt-1">{{ $wager->description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-1">
                                <div>
                                    <h4 class="font-medium text-emerald-700 dark:text-emerald-300 mb-2">Winners</h4>
                                    @if (($results['winners'] ?? collect())->isNotEmpty())
                                        <ul class="space-y-3">
                                            @foreach ($results['winners'] as $row)
                                                <li
                                                    class="flex items-center justify-between rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 bg-emerald-50/40 dark:bg-emerald-900/10 px-3 py-2">
                                                    <span
                                                        class="text-slate-900 dark:text-slate-100 font-medium">{{ $row['name'] }}</span>
                                                    <span
                                                        class="text-emerald-700 dark:text-emerald-300 text-sm">+{{ number_format($row['net'], 0) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-slate-500">No winners</p>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="font-medium text-rose-700 dark:text-rose-300 mb-2">Losers</h4>
                                    @if (($results['losers'] ?? collect())->isNotEmpty())
                                        <ul class="space-y-3">
                                            @foreach ($results['losers'] as $row)
                                                <li
                                                    class="flex items-center justify-between rounded-lg border border-rose-200/60 dark:border-rose-800/60 bg-rose-50/40 dark:bg-rose-900/10 px-3 py-2">
                                                    <span
                                                        class="text-slate-900 dark:text-slate-100 font-medium">{{ $row['name'] }}</span>
                                                    <span
                                                        class="text-rose-700 dark:text-rose-300 text-sm">{{ number_format($row['net'], 0) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-slate-500">No losers</p>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-slate-900 dark:text-slate-100 mb-2">Participants</h4>
                                @if ($wager->players->isNotEmpty())
                                    <ul class="flex flex-wrap gap-2">
                                        @foreach ($wager->players as $p)
                                            <li
                                                class="px-3 py-1.5 rounded-full border border-slate-200/80 dark:border-slate-700/80 bg-white/80 dark:bg-slate-800/80 text-sm text-slate-700 dark:text-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                                                {{ optional($p->user)->name ?? 'Unknown' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-slate-500">No participants</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($wager->status !== 'ended')
                        @if (!$isJoined)
                            <div
                                class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 p-6 text-center">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Join this wager to
                                    participate</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">You can view details, but
                                    you must join before placing a bet.</p>
                                <form method="POST" action="{{ route('wagers.join', $wager) }}"
                                    class="mt-4 inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium shadow-sm transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Join Wager
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- BET FORM -->
                            <form method="POST" action="{{ route('wagers.bet', $wager) }}" class="space-y-8"
                                id="bet-form">
                                @csrf
                                @method('POST')

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
                                                        class="bet-input block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                                        placeholder="Bet amount" min="0" step="1">
                                                    <input type="hidden" name="bets[{{ $loop->index }}][choice_id]"
                                                        value="{{ $choice->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- PLACE BET BUTTON -->
                                    <div class="flex items-center gap-3">
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
                                </div>
                            </form>

                            <!-- CREATOR CONTROLS - OUTSIDE THE BET FORM -->
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
                                            <button type="submit"
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

                                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 text-center">
                                        Only visible to you • Creator controls
                                    </p>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // FIXED VERSION - Global variables
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

        // Create or update pie chart
        function renderChart(data) {
            const canvas = document.getElementById('betChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Could not get canvas context');
                return;
            }

            // Prepare chart data
            let labels = data.map(d => d.label || 'Unknown');
            let values = data.map(d => parseFloat(d.total_bet) || 0);
            const totalBets = values.reduce((a, b) => a + b, 0);

            console.log('Rendering chart with:', {
                labels,
                values,
                totalBets
            });

            // Show placeholder if no bets
            if (totalBets === 0) {
                labels = ['No bets yet'];
                values = [1];
            }

            // Destroy old chart
            if (betChart) {
                betChart.destroy();
                betChart = null;
            }

            // Get theme
            const isDark = document.documentElement.classList.contains('dark');
            const backgroundColor = totalBets === 0 ? ['#E5E7EB'] : chartColors.slice(0, values.length);
            const borderColor = isDark ? '#1e293b' : '#ffffff';

            // Create chart
            try {
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
                console.log('Chart created successfully');
            } catch (error) {
                console.error('Error creating chart:', error);
            }
        }

        // Update chart with user's bet inputs
        function updateChartWithUserBets() {
            const inputs = document.querySelectorAll('.bet-input');
            const userBets = {};

            inputs.forEach(input => {
                const amount = parseFloat(input.value) || 0;
                const hiddenInput = input.parentElement.querySelector('input[name*="[choice_id]"]');
                if (hiddenInput) {
                    const choiceId = parseInt(hiddenInput.value);
                    userBets[choiceId] = amount;
                }
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
                const data = await response.json();
                console.log('Fetched stats:', data);
                return data;
            } catch (error) {
                console.error('Error fetching stats:', error);
                return null;
            }
        }

        // Update UI with server data
        function updateUIWithServerData(data) {
            if (!data) return;

            const potDisplay = document.getElementById('pot-display');
            if (potDisplay && data.pot !== undefined) {
                potDisplay.textContent = Math.round(data.pot).toLocaleString();
            }

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
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');

            if (!submitBtn || !submitText || !submitSpinner) {
                console.error('Missing form elements');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Placing Bet...';
            submitSpinner.classList.remove('hidden');

            try {
                // Manually collect all bet data
                const bets = [];
                const betInputs = document.querySelectorAll('input[name^="bets["]');

                // Group bet inputs by their index
                const betGroups = {};
                betInputs.forEach(input => {
                    const match = input.name.match(/bets\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const [_, index, type] = match;
                        if (!betGroups[index]) {
                            betGroups[index] = {};
                        }
                        betGroups[index][type] = input.value;
                    }
                });

                // Convert to array of bets
                Object.values(betGroups).forEach(bet => {
                    if (bet.amount && bet.choice_id) {
                        bets.push({
                            amount: bet.amount,
                            choice_id: bet.choice_id
                        });
                    }
                });

                if (bets.length === 0) {
                    throw new Error('Please enter at least one bet amount');
                }

                // Create form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');

                // Add bets to form data
                bets.forEach((bet, index) => {
                    formData.append(`bets[${index}][amount]`, bet.amount);
                    formData.append(`bets[${index}][choice_id]`, bet.choice_id);
                });

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                const contentType = response.headers.get('content-type') || '';
                let result = null;

                if (contentType.includes('application/json')) {
                    result = await response.json();
                }

                if (response.ok && result && result.success) {
                    // Update UI with new data
                    if (result.wager) {
                        const potDisplay = document.getElementById('pot-display');
                        if (potDisplay && result.wager.pot !== undefined) {
                            potDisplay.textContent = Math.round(result.wager.pot).toLocaleString();
                        }

                        if (result.wager.choices && Array.isArray(result.wager.choices)) {
                            initialData = result.wager.choices.map(choice => ({
                                id: choice.id,
                                label: choice.label,
                                total_bet: parseFloat(choice.total_bet) || 0
                            }));
                            renderChart(initialData);
                        }

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

                    // Show success notification
                    const successDiv = document.createElement('div');
                    successDiv.className =
                        'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    successDiv.textContent = 'Bet placed successfully!';
                    document.body.appendChild(successDiv);
                    setTimeout(() => successDiv.remove(), 3000);
                } else {
                    const errorMsg = result?.message || 'Failed to place bet';
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error placing bet:', error);
                alert('An error occurred while placing the bet');
            } finally {
                // Reset button state
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
                if (data) {
                    updateUIWithServerData(data);
                }
            }, 10000); // Poll every 10 seconds
        }

        // Stop polling
        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');

            // Wait for Chart.js to be ready
            setTimeout(() => {
                // Render initial chart
                renderChart(initialData);

                // Set up bet input listeners
                const inputs = document.querySelectorAll('.bet-input');
                console.log('Found bet inputs:', inputs.length);

                inputs.forEach(input => {
                    input.addEventListener('input', updateChartWithUserBets);
                    input.addEventListener('change', updateChartWithUserBets);
                });

                // Set up form submission
                const form = document.getElementById('bet-form');
                if (form) {
                    form.addEventListener('submit', handleBetSubmit);
                    console.log('Form listener attached');
                }

                // Start polling
                startPolling();

                // Handle visibility changes
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        stopPolling();
                    } else {
                        startPolling();
                    }
                });

                // Clean up on unload
                window.addEventListener('beforeunload', stopPolling);
            }, 300);
        });
    </script>

    <!-- End Wager Modal -->
    <div id="endWagerModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 border border-slate-200/60 dark:border-slate-700/60">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">End Wager</h3>
                <button type="button" onclick="closeEndWagerModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="endWagerForm" action="{{ route('wagers.end', $wager) }}" method="POST" class="space-y-6" onsubmit="return handleEndWagerSubmit(event)">
                @csrf
                
                <!-- Form Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    There was an error ending the wager:
                                </h4>
                                <ul class="mt-1 text-sm text-red-700 dark:text-red-300 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800/40 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    Warning: This action cannot be undone
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                    Select the winning option to finalize this wager and distribute winnings.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-3">
                            Select Winning Choice
                        </label>
                        @foreach ($wager->choices as $choice)
                            <label for="choice-{{ $choice->id }}"
                                class="flex items-center p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-all duration-200 group">
                                <input id="choice-{{ $choice->id }}" name="winning_choice_id" type="radio"
                                    value="{{ $choice->id }}"
                                    class="h-4 w-4 text-emerald-600 focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-600"
                                    required>
                                <span
                                    class="ml-3 text-sm font-medium text-slate-900 dark:text-slate-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $choice->label }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEndWagerModal()"
                            class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            End Wager Now
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // End Wager Modal Functions
        function handleEndWagerSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'An error occurred while processing your request.';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join(' ');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 mb-4';
                errorDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${errorMessage}</p>
                        </div>
                    </div>
                `;
                
                // Remove any existing error messages
                const existingError = form.querySelector('.bg-red-50');
                if (existingError) {
                    existingError.remove();
                }
                
                // Insert error message after the form
                form.insertBefore(errorDiv, form.firstChild);
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
            
            return false;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-show modal if show_end_modal is in session
            @if(session('show_end_modal'))
                const modal = document.getElementById('endWagerModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
                }
            @endif
            const modal = document.getElementById('endWagerModal');
            const endWagerButton = document.getElementById('endWagerButton');
            
            if (endWagerButton) {
                endWagerButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openEndWagerModal();
                });
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEndWagerModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEndWagerModal();
                }
            });
        });
        
        function openEndWagerModal() {
            const modal = document.getElementById('endWagerModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Focus the first input when modal opens
                const firstInput = modal.querySelector('input[type="radio"]');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        }
        
        function closeEndWagerModal() {
            const modal = document.getElementById('endWagerModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                // Reset form when closing
                const form = document.getElementById('endWagerForm');
                if (form) {
                    form.reset();
                }
            }
        }
    </script>
</x-app-layout>
