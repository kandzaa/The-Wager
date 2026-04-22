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
        <div x-show="maxBuyin > 0" class="flex items-center gap-3 min-w-[220px]">
            <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 shrink-0 dark:invert">
            <div class="flex-1">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Max buy-in</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400"
                        x-text="buyin >= maxBuyin ? 'Any' : (buyin === 0 ? 'Free only' : buyin.toLocaleString() + ' coins')">
                    </span>
                </div>
                <input type="range" x-model.number="buyin" @input="filter()"
                    :min="0" :max="maxBuyin" :step="step"
                    class="w-full h-1.5 rounded-full appearance-none cursor-pointer accent-emerald-500
                           bg-slate-200 dark:bg-white/[0.08]"/>
            </div>
        </div>

    </div>

</div>

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
                const name    = (item.getAttribute('data-name') || '').toLowerCase();
                const creator = (item.getAttribute('data-creator') || '').toLowerCase();
                const itemBuyin = parseInt(item.getAttribute('data-buyin') || '0', 10);

                const matchText  = !term || name.includes(term) || creator.includes(term);
                const matchBuyin = this.maxBuyin === 0 || itemBuyin <= this.buyin;

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
