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
                    <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-10 h-10 mb-1 shrink-0 dark:invert">
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
                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Free 100 coins every 3 hours</p>
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
                    <div id="claim-status">
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
                <div id="progress-wrap" class="mt-4 {{ $canClaim ? 'hidden' : '' }}">
                    <div class="w-full h-1 bg-slate-100 dark:bg-white/[0.05] rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-1000" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="fade-up mt-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2" style="animation-delay:240ms">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('transfer_success'))
        <div class="fade-up mt-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2" style="animation-delay:240ms">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('transfer_success') }}
        </div>
        @endif

        @if(session('transfer_error'))
        <div class="fade-up mt-4 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 text-sm flex items-center gap-2" style="animation-delay:240ms">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('transfer_error') }}
        </div>
        @endif

        {{-- Incoming Transfer Requests --}}
        @if($incoming->count())
        <div class="fade-up mt-4 rounded-2xl bg-white dark:bg-white/[0.03] border border-amber-200 dark:border-amber-500/20 overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:200ms">
            <div class="px-6 py-5 border-b border-amber-100 dark:border-amber-500/10 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.15em] text-amber-500 font-semibold">Incoming</p>
                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Coin Requests</p>
                </div>
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-black flex items-center justify-center">{{ $incoming->count() }}</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                @foreach($incoming as $transfer)
                <div class="px-6 py-4 flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="text-sm font-black text-emerald-700 dark:text-emerald-400">{{ strtoupper(substr($transfer->sender->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                            <span class="text-emerald-600 dark:text-emerald-400">+{{ number_format($transfer->amount) }}</span> coins from {{ $transfer->sender->name }}
                        </p>
                        @if($transfer->message)
                        <p class="text-xs text-slate-500 mt-0.5 italic">"{{ $transfer->message }}"</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">{{ $transfer->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <form action="{{ route('transfers.accept', $transfer) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg transition-all duration-200">Accept</button>
                        </form>
                        <form action="{{ route('transfers.decline', $transfer) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/[0.05] hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 border border-slate-200 dark:border-white/[0.06] rounded-lg transition-all duration-200">Decline</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Send Coins --}}
        @if($friends->count())
        <div class="fade-up mt-4 rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:240ms"
             x-data="{ open: false }">
            <button type="button" @click="open = !open"
                class="w-full px-6 py-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">Friends</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Send Coins</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse>
                <form action="{{ route('transfers.send') }}" method="POST" class="px-6 pb-6 space-y-4 border-t border-slate-100 dark:border-white/[0.05] pt-5">
                    @csrf
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Recipient</label>
                        <div class="relative">
                            <select name="recipient_id" required
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 appearance-none cursor-pointer">
                                <option value="" disabled selected class="bg-white dark:bg-[#0c0e12]">Choose a friend…</option>
                                @foreach($friends as $friend)
                                <option value="{{ $friend->id }}" class="bg-white dark:bg-[#0c0e12]">{{ $friend->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Amount</label>
                        <input type="number" name="amount" min="1" max="{{ Auth::user()->balance }}" required placeholder="How many coins?"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)] placeholder-slate-400" />
                    </div>
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Message <span class="normal-case font-normal text-slate-400">(optional)</span></label>
                        <input type="text" name="message" maxlength="255" placeholder="Add a note…"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)] placeholder-slate-400" />
                    </div>
                    <button type="submit"
                        class="w-full py-3 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 hover:shadow-lg hover:shadow-emerald-900/20 hover:-translate-y-px active:translate-y-0 transition-all duration-200">
                        Send Coins
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Sent transfers history --}}
        @if($sent->count())
        <div class="fade-up mt-4 rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:280ms">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/[0.05]">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">Recent Sent</p>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                @foreach($sent as $transfer)
                <div class="px-6 py-3.5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/[0.05] flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-slate-600 dark:text-slate-400">{{ strtoupper(substr($transfer->recipient->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            <span class="font-bold">{{ number_format($transfer->amount) }}</span> → {{ $transfer->recipient->name }}
                        </p>
                        @if($transfer->message)
                        <p class="text-xs text-slate-400 truncate italic">"{{ $transfer->message }}"</p>
                        @endif
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $transfer->status === 'accepted' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : '' }}
                        {{ $transfer->status === 'declined' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : '' }}
                        {{ $transfer->status === 'pending'  ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : '' }}
                    ">{{ ucfirst($transfer->status) }}</span>
                </div>
                @endforeach
            </div>
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
        const totalDuration = 3 * 60 * 60 * 1000;
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
                if (balanceEl && typeof data.balance === 'number') balanceEl.textContent = Number(data.balance).toLocaleString();
                btn.disabled = true;
                btn.textContent = 'Claimed';
                btn.className = 'relative px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed bg-slate-100 dark:bg-white/[0.05] border border-slate-200 dark:border-white/[0.08] text-slate-400';
                const statusDiv = document.getElementById('claim-status');
                if (statusDiv) statusDiv.innerHTML = `<p class="text-xs text-slate-500 mb-0.5">Next claim in</p><p id="cooldownText" class="text-lg font-black text-slate-700 dark:text-slate-300 tabular-nums">--:--:--</p>`;
                document.getElementById('progress-wrap')?.classList.remove('hidden');
                startCountdown(data.next_eligible_at);
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