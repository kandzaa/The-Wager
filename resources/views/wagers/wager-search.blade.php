<div class="mb-8 fade-up" style="animation-delay:40ms" x-data="wagerFilter()" x-init="init()">

    <div class="flex flex-wrap items-center gap-4">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px] max-w-md">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" x-model="query" @input="filter()"
                placeholder="Search wagers..."
                autocomplete="off"
                class="w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium
                       bg-white dark:bg-white/[0.04]
                       border border-slate-200 dark:border-white/[0.08]
                       text-slate-900 dark:text-white
                       placeholder-slate-400 dark:placeholder-slate-600
                       focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-500/60
                       focus:ring-2 focus:ring-emerald-500/20
                       shadow-sm dark:shadow-none transition-all duration-200"/>
        </div>

        {{-- Buy-in slider --}}
        <div x-show="maxBuyin > 0"
             class="flex-1 min-w-[260px] rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] px-5 py-4 shadow-sm dark:shadow-none">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 shrink-0 dark:invert">
                    <span class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400">Max buy-in</span>
                </div>
                <span class="text-sm font-black px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400"
                    x-text="buyin >= maxBuyin ? 'Any' : (buyin === 0 ? 'Free only' : buyin.toLocaleString() + ' coins')">
                </span>
            </div>

            <div class="relative">
                {{-- Track background --}}
                <div class="relative h-3 rounded-full bg-slate-100 dark:bg-white/[0.06] border border-slate-200 dark:border-white/[0.05]">
                    {{-- Filled portion --}}
                    <div class="absolute left-0 top-0 h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-75 pointer-events-none"
                         :style="`width: ${maxBuyin > 0 ? (buyin / maxBuyin * 100) : 100}%`">
                    </div>
                </div>
                {{-- Actual range input overlaid --}}
                <input type="range" x-model.number="buyin" @input="filter()"
                    :min="0" :max="maxBuyin" :step="step"
                    class="buyin-slider absolute inset-0 w-full opacity-0 cursor-pointer" style="height:100%"/>
            </div>

            <div class="flex justify-between mt-2 text-xs text-slate-400 dark:text-slate-600 font-medium">
                <span>Free</span>
                <span x-text="maxBuyin.toLocaleString() + ' coins'"></span>
            </div>
        </div>

    </div>

</div>

<style>
.buyin-slider {
    -webkit-appearance: none;
    appearance: none;
    height: 44px !important;
    margin: -20px 0;
    background: transparent !important;
    cursor: pointer;
}
.buyin-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #10b981;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(16,185,129,0.45), 0 1px 3px rgba(0,0,0,0.15);
    cursor: grab;
    transition: transform 0.15s, box-shadow 0.15s;
}
.buyin-slider::-webkit-slider-thumb:active {
    cursor: grabbing;
    transform: scale(1.2);
    box-shadow: 0 4px 16px rgba(16,185,129,0.55), 0 2px 6px rgba(0,0,0,0.2);
}
.buyin-slider::-moz-range-thumb {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #10b981;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(16,185,129,0.45);
    cursor: grab;
    transition: transform 0.15s;
}
.buyin-slider::-moz-range-thumb:active {
    cursor: grabbing;
    transform: scale(1.2);
}
</style>

<script>
function wagerFilter() {
    return {
        query: '',
        buyin: 0,
        maxBuyin: 0,
        step: 1,

        init() {
            const values = [...document.querySelectorAll('.wager-item')]
                .map(el => parseInt(el.getAttribute('data-buyin') || '0', 10))
                .filter(v => v > 0);

            if (values.length) {
                this.maxBuyin = Math.max(...values);
                this.buyin    = this.maxBuyin;
                this.step     = this.maxBuyin > 1000 ? Math.ceil(this.maxBuyin / 100) : 1;
            }
        },

        filter() {
            const term = this.query.toLowerCase();
            let visible = 0;

            document.querySelectorAll('.wager-item').forEach(item => {
                const name      = (item.getAttribute('data-name') || '').toLowerCase();
                const creator   = (item.getAttribute('data-creator') || '').toLowerCase();
                const itemBuyin = parseInt(item.getAttribute('data-buyin') || '0', 10);

                const matchText  = !term || name.includes(term) || creator.includes(term);
                const matchBuyin = this.maxBuyin === 0
                    || this.buyin >= this.maxBuyin          // slider at max → show all
                    || (this.buyin === 0 && itemBuyin === 0) // slider at 0 → free only
                    || (this.buyin > 0 && itemBuyin > 0 && itemBuyin <= this.buyin); // paid range

                const show = matchText && matchBuyin;
                item.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            const empty = document.querySelector('.no-wagers-message');
            if (empty) empty.style.display = visible ? 'none' : 'block';
        }
    };
}
</script>
