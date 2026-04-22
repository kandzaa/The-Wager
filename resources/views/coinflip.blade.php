<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 right-1/3 w-[600px] h-[500px] bg-amber-900/10 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-emerald-950/15 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-6 py-14">

        {{-- Header --}}
        <div class="mb-10 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-amber-500 font-bold mb-2">Mini-game</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Coinflip</h1>
            <p class="text-sm text-slate-500 mt-1">50/50 against the house — double or nothing</p>
        </div>

        {{-- Game card --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-8 shadow-sm dark:shadow-none mb-6" style="animation-delay:60ms"
             x-data="coinflip()">

            {{-- Coin --}}
            <div class="flex justify-center mb-8">
                <div class="relative w-32 h-32" id="coin-wrap">
                    <div id="coin" class="w-32 h-32 relative" style="transform-style:preserve-3d; transition: transform 0.8s cubic-bezier(.4,0,.2,1);">
                        {{-- Heads --}}
                        <div class="absolute inset-0 rounded-full flex items-center justify-center text-5xl font-black select-none
                                    bg-gradient-to-br from-amber-300 to-amber-500 border-4 border-amber-400 shadow-xl"
                             style="backface-visibility:hidden">
                            H
                        </div>
                        {{-- Tails --}}
                        <div class="absolute inset-0 rounded-full flex items-center justify-center text-5xl font-black select-none
                                    bg-gradient-to-br from-slate-300 to-slate-500 dark:from-slate-600 dark:to-slate-800 border-4 border-slate-400 shadow-xl"
                             style="backface-visibility:hidden; transform:rotateY(180deg)">
                            T
                        </div>
                    </div>
                </div>
            </div>

            {{-- Result banner --}}
            <div id="result-banner" class="mb-6 text-center" style="display:none">
                <p id="result-text" class="text-2xl font-black mb-1"></p>
                <p id="result-sub" class="text-sm text-slate-500"></p>
            </div>

            {{-- Pick --}}
            <div class="flex gap-3 mb-5">
                <button @click="pick = 'heads'" type="button"
                    :class="pick === 'heads'
                        ? 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-500/25'
                        : 'bg-white dark:bg-white/[0.04] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/[0.08] hover:border-amber-400 dark:hover:border-amber-500/40'"
                    class="flex-1 py-3 rounded-xl text-sm font-bold border-2 transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="text-lg">🪙</span> Heads
                </button>
                <button @click="pick = 'tails'" type="button"
                    :class="pick === 'tails'
                        ? 'bg-slate-600 dark:bg-slate-500 text-white border-slate-600 dark:border-slate-500 shadow-lg'
                        : 'bg-white dark:bg-white/[0.04] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/[0.08] hover:border-slate-400 dark:hover:border-slate-500/60'"
                    class="flex-1 py-3 rounded-xl text-sm font-bold border-2 transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="text-lg">🔘</span> Tails
                </button>
            </div>

            {{-- Amount --}}
            <div class="mb-5">
                <div class="flex items-center gap-2 mb-2">
                    <label class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400 flex-1">Bet Amount</label>
                    <span class="text-xs text-slate-400">Balance: <span class="font-bold text-emerald-600 dark:text-emerald-400" id="balance-display">{{ number_format(Auth::user()->balance) }}</span> coins</span>
                </div>
                <div class="flex gap-2">
                    <input type="number" x-model.number="amount" min="1" :max="balance"
                        class="flex-1 px-4 py-3 rounded-xl text-sm font-medium bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition-all"
                        placeholder="Enter amount">
                    <button @click="amount = Math.floor(balance / 2)" type="button"
                        class="px-3 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-white/[0.05] text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.07] transition-all">
                        ½
                    </button>
                    <button @click="amount = balance" type="button"
                        class="px-3 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-white/[0.05] text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.07] transition-all">
                        Max
                    </button>
                </div>
            </div>

            {{-- Flip button --}}
            <button @click="doFlip()" :disabled="flipping || !pick || amount < 1 || amount > balance"
                class="w-full py-3.5 rounded-xl text-sm font-black uppercase tracking-[0.1em] transition-all duration-200 active:scale-95
                       bg-amber-500 hover:bg-amber-400 text-white shadow-lg shadow-amber-500/25
                       disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
                <span x-text="flipping ? 'Flipping...' : (pick ? 'Flip — ' + pick.charAt(0).toUpperCase() + pick.slice(1) : 'Pick a side first')"></span>
            </button>

        </div>

        {{-- History --}}
        @if($history->isNotEmpty())
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] shadow-sm dark:shadow-none overflow-hidden" style="animation-delay:120ms">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/[0.05] flex items-center gap-3">
                <div class="w-1.5 h-5 bg-slate-400 dark:bg-slate-600 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Recent Games</h2>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                @foreach($history as $game)
                <div class="flex items-center gap-4 px-6 py-3.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black shrink-0
                        {{ $game->won ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 border border-red-200 dark:border-red-500/20' }}">
                        {{ $game->result === 'heads' ? 'H' : 'T' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            Picked <span class="capitalize">{{ $game->pick }}</span> — landed <span class="capitalize">{{ $game->result }}</span>
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($game->created_at)->diffForHumans() }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black {{ $game->won ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                            {{ $game->won ? '+' : '' }}{{ number_format($game->payout) }}
                        </p>
                        <p class="text-xs text-slate-400">{{ number_format($game->amount) }} bet</p>
                    </div>
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

@keyframes spin-to-heads {
    0%   { transform: rotateY(0deg); }
    100% { transform: rotateY(1440deg); }
}
@keyframes spin-to-tails {
    0%   { transform: rotateY(0deg); }
    100% { transform: rotateY(1620deg); }
}
.coin-spinning-heads { animation: spin-to-heads 0.9s cubic-bezier(.4,0,.2,1) forwards; }
.coin-spinning-tails { animation: spin-to-tails 0.9s cubic-bezier(.4,0,.2,1) forwards; }
</style>

<script>
function coinflip() {
    return {
        pick: null,
        amount: 100,
        flipping: false,
        balance: {{ Auth::user()->balance }},

        async doFlip() {
            if (this.flipping || !this.pick || this.amount < 1 || this.amount > this.balance) return;
            this.flipping = true;

            document.getElementById('result-banner').style.display = 'none';

            const coin = document.getElementById('coin');

            try {
                const res = await fetch('{{ route('coinflip.flip') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ amount: this.amount, pick: this.pick }),
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Error');

                // Reset to base state, force reflow, then play the correct animation
                coin.className = 'w-32 h-32 relative';
                coin.style.transform = '';
                void coin.offsetWidth;
                coin.className = 'w-32 h-32 relative ' + (data.result === 'heads' ? 'coin-spinning-heads' : 'coin-spinning-tails');

                setTimeout(() => {
                    const banner  = document.getElementById('result-banner');
                    const text    = document.getElementById('result-text');
                    const sub     = document.getElementById('result-sub');
                    const display = document.getElementById('balance-display');

                    if (data.won) {
                        text.textContent = '🎉 You won ' + data.amount.toLocaleString() + ' coins!';
                        text.className   = 'text-2xl font-black mb-1 text-emerald-600 dark:text-emerald-400';
                        sub.textContent  = data.result.charAt(0).toUpperCase() + data.result.slice(1) + ' — correct!';
                    } else {
                        text.textContent = '💸 You lost ' + data.amount.toLocaleString() + ' coins';
                        text.className   = 'text-2xl font-black mb-1 text-red-500 dark:text-red-400';
                        sub.textContent  = data.result.charAt(0).toUpperCase() + data.result.slice(1) + ' — better luck next time';
                    }

                    banner.style.display = 'block';
                    this.balance = data.new_balance;
                    display.textContent  = data.new_balance.toLocaleString();
                    this.flipping = false;
                }, 950);

            } catch (err) {
                coin.className = 'w-32 h-32 relative';
                this.flipping  = false;
                alert(err.message);
            }
        }
    };
}
</script>
</x-app-layout>
