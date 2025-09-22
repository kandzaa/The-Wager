<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 transition-colors">
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
                    class="relative px-8 py-8 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 border-b border-slate-200/50 dark:border-slate-700/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-blue-500/5 dark:from-emerald-400/5 dark:to-blue-400/5">
                    </div>
                    <div class="relative">
                        <h1
                            class="text-3xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 dark:from-slate-100 dark:via-slate-300 dark:to-slate-100 bg-clip-text text-transparent">
                            {{ $wager->name }}
                        </h1>
                        <p class="mt-3 text-lg text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                            {{ $wager->description }}
                        </p>

                        <div class="absolute top-8 right-8">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-white/80 dark:bg-slate-800/70 backdrop-blur-sm border border-slate-200/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 shadow-sm">
                                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                </svg>
                                <span>Created by: <strong
                                        class="font-semibold">{{ optional($wager->creator)->name ?? 'Unknown' }}</strong></span>
                            </span>
                        </div>

                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-4 border border-slate-200/50 dark:border-slate-700/50">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Ends</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ optional($wager->ending_time)?->diffForHumans() ?? 'N/A' }}
                                </div>
                            </div>

                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-4 border border-slate-200/50 dark:border-slate-700/50">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Status</div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span
                                        class="w-2 h-2 rounded-full {{ $wager->status === 'public' ? 'bg-emerald-500 shadow-lg shadow-emerald-500/30' : 'bg-slate-400' }}"></span>
                                    <span
                                        class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ ucfirst($wager->status) }}</span>
                                </div>
                            </div>

                            <div
                                class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-lg p-4 border border-slate-200/50 dark:border-slate-700/50">
                                <div
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Max Players</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $wager->max_players }}
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 backdrop-blur-sm rounded-lg p-4 border border-emerald-200/50 dark:border-emerald-700/50">
                                <div
                                    class="text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">
                                    Total Pot</div>
                                <div class="mt-1 text-lg font-bold text-emerald-800 dark:text-emerald-200">
                                    {{ number_format($wager->pot, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="px-8 py-8 border-t border-slate-200/50 dark:border-slate-700/50">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Live Bet Distribution</h2>
                    <div class="relative w-[300px] max-w-1xl mx-auto">
                        <canvas id="wagerPieChart" class="w-full"></canvas>
                    </div>
                </div>

                @php
                    $playersList = collect($wager->players ?? []);
                    $isJoined = $playersList->contains(function ($p) {
                        return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
                    });
                @endphp

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
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium shadow-sm">
                                    Join Wager
                                </button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('wagers.bet', $wager) }}" class="space-y-8">
                            @csrf

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach ($wager->choices as $choice)
                                        <label
                                            class="group relative flex items-center gap-4 p-6 border-2 rounded-xl cursor-pointer transition-all duration-200 border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10 bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm hover:bg-white/80 dark:hover:bg-slate-800/80">

                                            <input type="radio" name="choice_id" value="{{ $choice->id }}"
                                                class="sr-only peer" required>

                                            <div
                                                class="relative w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all duration-200 flex-shrink-0">
                                                <div
                                                    class="absolute inset-1 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200">
                                                </div>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <span
                                                    class="block text-lg font-medium text-slate-900 dark:text-slate-100 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200">
                                                    {{ $choice->label }}
                                                </span>
                                            </div>

                                            <div
                                                class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-emerald-500 peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 transition-all duration-200">
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('choice_id')
                                    <p class="mt-2 text-sm text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Place Your Bet</h2>
                                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                                    <div class="flex-1 max-w-xs">
                                        <label for="amount"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                            Amount (coins)
                                        </label>
                                        <div class="text-xs mb-2 text-slate-500 dark:text-slate-400">
                                            Available balance: <span id="available-balance"
                                                class="font-semibold text-slate-700 dark:text-slate-200">{{ number_format((int) (Auth::user()->balance ?? 0)) }}</span>
                                        </div>
                                        <div class="relative">
                                            <input id="amount" name="amount" type="number" min="1"
                                                step="1" required
                                                class="w-full rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all duration-200 text-lg font-medium shadow-sm"
                                                placeholder="Enter coins">
                                            <div
                                                class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('amount')
                                            <p class="mt-1 text-sm text-rose-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button id="place-bet-btn" type="submit"
                                        class="group relative inline-flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white text-lg font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition-all duration-200 shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-500/30 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:transform-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Place Bet
                                        <div
                                            class="absolute inset-0 rounded-xl bg-gradient-to-r from-white/20 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        </div>
                                    </button>

                                </div>
                            </div>
                        </form>
                    @endif


                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Ievieto Chart.js bibliotēku, kas tiek izmantota diagrammu zīmēšanai -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // DOMContentLoaded laikā izpildīts kods 
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('wagerPieChart');
            if (!ctx) return;

            // Gaišās tēmas krāsas priekš sektoru diagrammas
            const lightThemeColors = [
                '#1f77b4', // blue
                '#ff7f0e', // orange
                '#2ca02c', // green
                '#d62728', // red
                '#9467bd', // purple
                '#8c564b', // brown
                '#e377c2', // pink
                '#17becf' // cyan
            ];

            // Tumšās tēmas krāsas priekš sektoru diagrammas
            const darkThemeColors = [
                '#4CC9F0', // sky blue
                '#F72585', // magenta
                '#43AA8B', // teal/green
                '#F9C74F', // yellow
                '#577590', // desaturated blue
                '#90BE6D', // light green
                '#FAA307', // amber
                '#E07A5F' // coral
            ];

            // Funkcija kas atgriež tēmas krāsas
            const getThemeColors = (isDarkTheme) => {
                return isDarkTheme ? darkThemeColors : lightThemeColors;
            };

            const cssVariables = `
:root {
  --color-1: #059669;
  --color-2: #2563EB;
  --color-3: #D97706;
  --color-4: #DC2626;
  --color-5: #7C3AED;
  --color-6: #0891B2;
  --color-7: #65A30D;
  --color-8: #DB2777;
}

[data-theme="dark"] {
  --color-1: #34D399;
  --color-2: #60A5FA;
  --color-3: #FBBF24;
  --color-4: #F87171;
  --color-5: #A78BFA;
  --color-6: #22D3EE;
  --color-7: #A3E635;
  --color-8: #F472B6;
}
`;
            // Pārbauda vai lietotājs izmanto tumšo tēmu
            const isDarkTheme = () => document.documentElement.classList.contains('dark') || document
                .documentElement.getAttribute('data-theme') === 'dark';

            // Iegūst krāsu paleti atkarībā no tēmas (tumšās/gaisas)
            let bgColors = getThemeColors(isDarkTheme());
            // Izveido tādu pašu krāsu masivu apmales krāsām
            let borderColors = bgColors.map(c => c);

            try {
                const styleId = 'wager-chart-theme-vars';
                if (!document.getElementById(styleId)) {
                    const styleEl = document.createElement('style');
                    styleEl.id = styleId;
                    styleEl.textContent = cssVariables;
                    document.head.appendChild(styleEl);
                }
            } catch (_) {}

            let chart;

            // Funkcija, kas iegūst datus par derībām no servera
            async function fetchStats() {
                try {
                    // Nosūta pieprasījumu, lai iegūtu datus
                    const res = await fetch("{{ route('wagers.stats', ['wager' => $wager->id]) }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!res.ok) throw new Error('Neizdevās ielādēt statistiku');
                    return await res.json(); // Atgriež datus JSON formātā
                } catch (e) {
                    // Atgriež tukšus datus, ja radusies kļūda
                    return {
                        labels: [],
                        data: [],
                        pot: 0
                    };
                }
            }

            // Nav parādīta pilna koda implementācija
            // Inicializē un parāda diagrammu ar norādītajiem datiem
            function initChart(labels, data) {
                // Izveido jaunu diagrammas (pie chart) instanci
                chart = new Chart(ctx, {
                    type: 'pie', // Norāda diagrammas tipu - sektora diagramma
                    data: {
                        labels,
                        datasets: [{
                            data,
                            // Pielieto krāsas no masīva, cikliski atkārtojot tās, ja nepieciešams
                            backgroundColor: labels.map((_, i) => bgColors[i % bgColors.length]),
                            // Tās pašas krāsas tiek izmantotas apmalēm
                            borderColor: labels.map((_, i) => borderColors[i % borderColors
                                .length]),
                            borderWidth: 2, // Apmales platums pikseļos
                            hoverOffset: 6, // Cik tālu izvirzīt daļu, kad uz tās novieto kursoru
                        }]
                    },
                    // Diagrammas konfigurācijas opcijas
                    options: {
                        responsive: true, // Diagramma automātiski pielāgojas izmēram
                        maintainAspectRatio: false, // Neuztur malu attiecību
                        animation: false, // Atspējo animācijas veiktspējas labad
                        animations: {
                            colors: false, // Atspējo krāsu animācijas
                            x: false, // Atspējo x ass animācijas
                            y: false, // Atspējo y ass animācijas
                            radius: {
                                duration: 0 // Atspējo rādiusa animācijas
                            },
                        },
                        transitions: {
                            active: {
                                animation: {
                                    duration: 0 // Atspējo pāreju animācijas
                                }
                            },
                        },
                        plugins: {
                            // Leģendas konfigurācija
                            legend: {
                                position: 'bottom', // Novieto leģendu zem diagrammas
                                labels: {
                                    // Izmanto pielāgotu krāsu no CSS mainīgajiem vai noklusējuma krāsu
                                    color: getComputedStyle(document.documentElement).getPropertyValue(
                                        '--tw-prose-body') || '#475569'
                                }
                            },
                            // Uzvedņu (tooltip) konfigurācija
                            tooltip: {
                                callbacks: {
                                    // Pielāgo tekstu, kas parādās, kad kursors novietots virs diagrammas daļas
                                    label: (ctx) => {
                                        const label = ctx.label || ''; // Iegūst etiķeti
                                        const value = ctx.parsed || 0; // Iegūst skaitlisko vērtību
                                        return `${label}: ${value}`; // Atgriež formatētu tekstu
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Atjauno diagrammas krāsas, mainoties tēmai (tumšā/gaisā)
            function updateColorsForTheme() {
                // Iegūst jaunās krāsas atbilstoši pašreizējai tēmai
                bgColors = getThemeColors(isDarkTheme());
                borderColors = bgColors.map(c => c);

                // Ja diagramma jau ir izveidota, atjauno tās krāsas
                if (chart) {
                    // Atjauno fona krāsas katram datu punktam
                    chart.data.datasets[0].backgroundColor = chart.data.labels.map((_, i) =>
                        bgColors[i % bgColors.length]);

                    // Atjauno apmales krāsas katram datu punktam
                    chart.data.datasets[0].borderColor = chart.data.labels.map((_, i) =>
                        borderColors[i % borderColors.length]);

                    // Atjauno diagrammu bez animācijas ('none' nozīmē bez animācijas)
                }
            }

            const themeObserver = new MutationObserver(() => updateColorsForTheme());
            themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class', 'data-theme']
            });

            function shallowEqualArray(a, b) {
                if (!Array.isArray(a) || !Array.isArray(b)) return false;
                if (a.length !== b.length) return false;
                for (let i = 0; i < a.length; i++) {
                    if (a[i] !== b[i]) return false;
                }
                return true;
            }

            // Atjauno diagrammu ar jaunākajiem datiem no servera
            async function refreshChart() {
                // Iegūst jaunākos datus
                const stats = await fetchStats();

                // Ja diagramma vēl nav izveidota, izveido to
                if (!chart) {
                    initChart(stats.labels, stats.data);
                } else {
                    // Pretējā gadījumā atjauno esošās diagrammas datus
                    // Only update if changed to avoid flicker
                    const sameLabels = shallowEqualArray(chart.data.labels, stats.labels);
                    const sameData = shallowEqualArray(chart.data.datasets[0].data, stats.data);
                    if (!sameLabels) chart.data.labels = stats.labels;
                    if (!sameData) chart.data.datasets[0].data = stats.data;
                    if (!sameLabels || !sameData) {
                        updateColorsForTheme();
                        chart.update('none');
                    }
                }
            }

            refreshChart();
            setInterval(refreshChart, 3000);

            const amountInput = document.getElementById('amount');
            const placeBetBtn = document.getElementById('place-bet-btn');
            const balanceError = document.getElementById('balance-error');
            const availableBalanceEl = document.getElementById('available-balance');
            const availableBalance = Number((availableBalanceEl?.textContent || '0').replace(/[^0-9]/g, '')) || 0;

            function validateBalance() {
                const val = Number(amountInput.value || 0);
                const ok = val > 0 && val <= availableBalance;
                if (placeBetBtn) placeBetBtn.disabled = !ok;
                if (balanceError) balanceError.classList.toggle('hidden', ok);
            }
            if (amountInput) {
                amountInput.addEventListener('input', validateBalance);
                validateBalance();
            }
        });
    </script>
</x-app-layout>
