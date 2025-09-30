<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-xl border border-slate-300/60 dark:border-slate-800">
                    <div class="p-8">
                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Current
                                        Balance</h3>
                                    <div class="flex items-center space-x-3">
                                        <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ Auth::user()->balance }}
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-300 text-lg">Coins</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- daily balance, katras 24h -->
                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Daily
                                        Coins</h3>
                                    <div class="flex items-center space-x-3" id="daily-claim"
                                         data-next-eligible-at="{{ optional($nextEligibleAt)->toIso8601String() }}"
                                         data-can-claim="{{ $canClaim ? '1' : '0' }}">
                                        @if (!$canClaim)
                                            <span id="cooldownText" class="text-slate-600 dark:text-slate-300 text-lg">Available in --:--:--</span>
                                        @endif
                                        <form id="claimForm" method="POST" action="{{ route('dailyBalance') }}" class="hidden">
                                            @csrf
                                        </form>
                                        <button id="claimBtn" type="button"
                                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                                                {{ $canClaim ? '' : 'disabled' }}>
                                            {{ $canClaim ? 'Claim' : 'Claimed' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const daily = document.getElementById('daily-claim');
            if (!daily) return;

            const balanceEl = document.querySelector('span.text-3xl.font-bold');
            const btn = document.getElementById('claimBtn');
            const form = document.getElementById('claimForm');
            const cooldownText = document.getElementById('cooldownText');
            const nextEligibleAtStr = daily.getAttribute('data-next-eligible-at');
            const canClaim = daily.getAttribute('data-can-claim') === '1';

            let countdownTimer = null;

            function startCountdown(untilISO) {
                if (!untilISO) return;
                const until = new Date(untilISO);
                function tick() {
                    const now = new Date();
                    const diffMs = until - now;
                    if (diffMs <= 0) {
                        if (countdownTimer) clearInterval(countdownTimer);
                        if (cooldownText) cooldownText.textContent = 'Available now';
                        btn.disabled = false;
                        btn.textContent = 'Claim';
                        return;
                    }
                    const totalSeconds = Math.floor(diffMs / 1000);
                    const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
                    const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
                    const s = String(totalSeconds % 60).padStart(2, '0');
                    if (cooldownText) cooldownText.textContent = `Available in ${h}:${m}:${s}`;
                }
                tick();
                countdownTimer = setInterval(tick, 1000);
            }

            // Initialize state from server values
            if (!canClaim) {
                btn.disabled = true;
                btn.textContent = 'Claimed';
                startCountdown(nextEligibleAtStr);
            }

            async function claim() {
                if (btn.disabled) return;
                btn.disabled = true;
                const tokenInput = form.querySelector('input[name="_token"]');
                const csrf = tokenInput ? tokenInput.value : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                    });
                    const data = await res.json();
                    if (res.ok) {
                        // Update balance and start cooldown from server time
                        if (balanceEl && typeof data.balance === 'number') {
                            balanceEl.textContent = data.balance;
                        }
                        const nextAt = data.last_daily_claim_at;
                        if (cooldownText) {
                            cooldownText.textContent = 'Available in 24:00:00';
                        } else {
                            const span = document.createElement('span');
                            span.id = 'cooldownText';
                            span.className = 'text-slate-600 dark:text-slate-300 text-lg';
                            span.textContent = 'Available in 24:00:00';
                            daily.insertBefore(span, btn);
                        }
                        btn.textContent = 'Claimed';
                        startCountdown(new Date(nextAt).toISOString().replace('Z',''));
                    } else if (res.status === 429) {
                        // Already claimed; start countdown from remaining seconds
                        btn.textContent = 'Claimed';
                        const nextAt = data.next_eligible_at;
                        if (cooldownText && nextAt) {
                            startCountdown(nextAt);
                        }
                    } else {
                        // Unexpected error
                        btn.disabled = false;
                        btn.textContent = 'Try again';
                    }
                } catch (e) {
                    btn.disabled = false;
                    btn.textContent = 'Try again';
                }
            }

            btn?.addEventListener('click', claim);
        })();
    </script>
</x-app-layout>
