<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-emerald-900/20 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-xl mx-auto px-6 py-16">

        <div class="mb-10 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-2">Wallet</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Your Balance</h1>
        </div>

        {{-- Balance Card --}}
        <div class="fade-up mb-4 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 dark:from-emerald-900/60 dark:to-emerald-950/40 border border-emerald-500/30 p-8 relative overflow-hidden shadow-lg shadow-emerald-900/20" style="animation-delay:80ms">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
            <div class="relative">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100/70 dark:text-emerald-500/70 font-semibold mb-3">Available</p>
                <div class="flex items-end gap-3 mb-2">
                    <span class="text-6xl font-black text-white leading-none">{{ Auth::user()->balance }}</span>
                    <span class="text-emerald-200 dark:text-emerald-400 font-semibold text-lg mb-1">coins</span>
                </div>
                <p class="text-emerald-100/60 dark:text-slate-500 text-sm">{{ Auth::user()->email }}</p>
            </div>
        </div>

        {{-- Daily Claim --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:160ms">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-white/[0.05] flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">Daily Bonus</p>
                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Free coins every 24 hours</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="px-6 py-5"
                 id="daily-claim"
                 data-next-eligible-at="{{ optional($nextEligibleAt)->toIso8601String() }}"
                 data-can-claim="{{ $canClaim ? '1' : '0' }}">
                <div class="flex items-center justify-between">
                    <div>
                        @if(!$canClaim)
                            <p class="text-xs text-slate-500 mb-0.5">Next claim in</p>
                            <p id="cooldownText" class="text-lg font-black text-slate-700 dark:text-slate-300 tabular-nums">--:--:--</p>
                        @else
                            <p class="text-xs text-emerald-600 dark:text-emerald-500 mb-0.5">Ready to claim</p>
                            <p class="text-lg font-black text-emerald-700 dark:text-emerald-300">Available now!</p>
                        @endif
                    </div>
                    <form id="claimForm" method="POST" action="{{ url('/dailyBalance') }}" class="hidden">@csrf</form>
                    <button id="claimBtn" type="button"
                        class="relative px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed
                               {{ $canClaim ? 'bg-emerald-600 hover:bg-emerald-500 text-white hover:shadow-lg hover:shadow-emerald-900/30' : 'bg-slate-100 dark:bg-white/[0.05] border border-slate-200 dark:border-white/[0.08] text-slate-400' }}"
                        {{ $canClaim ? '' : 'disabled' }}>
                        {{ $canClaim ? '+ Claim coins' : 'Claimed' }}
                    </button>
                </div>
                @if(!$canClaim)
                <div class="mt-4">
                    <div class="w-full h-1 bg-slate-100 dark:bg-white/[0.05] rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-1000" style="width:0%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="fade-up mt-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2" style="animation-delay:240ms">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

    </div>
</div>
<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>
<script>
(function() {
    const daily = document.getElementById('daily-claim');
    if (!daily) return;
    const balanceEl = document.querySelector('span.text-6xl');
    const btn = document.getElementById('claimBtn');
    const form = document.getElementById('claimForm');
    const canClaim = daily.getAttribute('data-can-claim') === '1';
    const nextEligibleAtStr = daily.getAttribute('data-next-eligible-at');
    let timer = null;

    function startCountdown(untilISO) {
        if (!untilISO) return;
        const until = new Date(untilISO);
        const totalDuration = 24 * 60 * 60 * 1000;
        function tick() {
            const diff = until - new Date();
            if (diff <= 0) {
                clearInterval(timer);
                const ct = document.getElementById('cooldownText');
                if (ct) ct.textContent = '00:00:00';
                btn.disabled = false;
                btn.textContent = '+ Claim coins';
                return;
            }
            const s = Math.floor(diff / 1000);
            const ct = document.getElementById('cooldownText');
            if (ct) ct.textContent = `${String(Math.floor(s/3600)).padStart(2,'0')}:${String(Math.floor((s%3600)/60)).padStart(2,'0')}:${String(s%60).padStart(2,'0')}`;
            const pb = document.getElementById('progressBar');
            if (pb) pb.style.width = Math.min(100, ((totalDuration - diff) / totalDuration) * 100) + '%';
        }
        tick();
        timer = setInterval(tick, 1000);
    }

    if (!canClaim) startCountdown(nextEligibleAtStr);

    async function claim() {
        if (btn.disabled) return;
        btn.disabled = true;
        btn.textContent = 'Claiming...';
        const csrf = form.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.content;
        try {
            const res = await fetch(form.action, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrf,'Accept':'application/json'} });
            const data = await res.json();
            if (res.ok) {
                if (balanceEl && typeof data.balance === 'number') balanceEl.textContent = data.balance;
                btn.textContent = 'Claimed';
                startCountdown(new Date(data.last_daily_claim_at).toISOString());
            } else if (res.status === 429) {
                btn.textContent = 'Claimed';
                if (data.next_eligible_at) startCountdown(data.next_eligible_at);
            } else { btn.disabled = false; btn.textContent = '+ Claim coins'; }
        } catch(e) { btn.disabled = false; btn.textContent = 'Try again'; }
    }
    btn?.addEventListener('click', claim);
})();
</script>
</x-app-layout>