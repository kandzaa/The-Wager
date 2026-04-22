<div class="mb-8 fade-up" style="animation-delay:40ms" x-data="wagerFilter()" x-init="init()">

    {{-- Search + filter row --}}
    <div class="flex flex-wrap items-center gap-3">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px] max-w-md">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" x-model.debounce.300ms="query"
                @input="filter()"
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

        {{-- Status filter --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button"
                :class="status !== 'all' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-white/[0.08] text-slate-600 dark:text-slate-400 bg-white dark:bg-white/[0.04]'"
                class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold border shadow-sm dark:shadow-none transition-all duration-200 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="status === 'all' ? 'Status' : (status === 'active' ? 'Active' : 'Ended')"></span>
                <svg class="w-3 h-3 opacity-50 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute left-0 top-full mt-2 w-36 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/[0.08] shadow-xl z-30 overflow-hidden py-1">
                <template x-for="opt in [{val:'all',label:'All'},{val:'active',label:'Active'},{val:'ended',label:'Ended'}]" :key="opt.val">
                    <button @click="status = opt.val; filter(); open = false" type="button"
                        :class="status === opt.val ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/[0.04]'"
                        class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                        x-text="opt.label">
                    </button>
                </template>
            </div>
        </div>

        {{-- Privacy filter --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button"
                :class="privacy !== 'all' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-white/[0.08] text-slate-600 dark:text-slate-400 bg-white dark:bg-white/[0.04]'"
                class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold border shadow-sm dark:shadow-none transition-all duration-200 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span x-text="privacy === 'all' ? 'Privacy' : (privacy === 'public' ? 'Public' : 'Private')"></span>
                <svg class="w-3 h-3 opacity-50 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute left-0 top-full mt-2 w-36 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/[0.08] shadow-xl z-30 overflow-hidden py-1">
                <template x-for="opt in [{val:'all',label:'All'},{val:'public',label:'Public'},{val:'private',label:'Private'}]" :key="opt.val">
                    <button @click="privacy = opt.val; filter(); open = false" type="button"
                        :class="privacy === opt.val ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/[0.04]'"
                        class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                        x-text="opt.label">
                    </button>
                </template>
            </div>
        </div>

        {{-- Buy-in filter --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button"
                :class="buyin !== 'all' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-white/[0.08] text-slate-600 dark:text-slate-400 bg-white dark:bg-white/[0.04]'"
                class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold border shadow-sm dark:shadow-none transition-all duration-200 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12V22H4V12M22 7H2v5h20V7zM12 22V7M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
                <span x-text="buyin === 'all' ? 'Buy-in' : (buyin === 'free' ? 'Free' : 'Paid')"></span>
                <svg class="w-3 h-3 opacity-50 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute left-0 top-full mt-2 w-36 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/[0.08] shadow-xl z-30 overflow-hidden py-1">
                <template x-for="opt in [{val:'all',label:'All'},{val:'free',label:'Free'},{val:'paid',label:'Paid'}]" :key="opt.val">
                    <button @click="buyin = opt.val; filter(); open = false" type="button"
                        :class="buyin === opt.val ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/[0.04]'"
                        class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                        x-text="opt.label">
                    </button>
                </template>
            </div>
        </div>

        {{-- Clear button — only when a filter is active --}}
        <button x-show="query || status !== 'all' || privacy !== 'all' || buyin !== 'all'"
            @click="query=''; status='all'; privacy='all'; buyin='all'; filter()"
            type="button"
            class="flex items-center gap-1.5 px-3 py-3 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            Clear
        </button>

    </div>

    {{-- Active filter chips --}}
    <div class="flex flex-wrap gap-2 mt-3" x-show="status !== 'all' || privacy !== 'all' || buyin !== 'all'">
        <template x-if="status !== 'all'">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                <span x-text="'Status: ' + status"></span>
                <button @click="status='all'; filter()" class="hover:text-red-500 transition-colors">&times;</button>
            </span>
        </template>
        <template x-if="privacy !== 'all'">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                <span x-text="'Privacy: ' + privacy"></span>
                <button @click="privacy='all'; filter()" class="hover:text-red-500 transition-colors">&times;</button>
            </span>
        </template>
        <template x-if="buyin !== 'all'">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                <span x-text="'Buy-in: ' + buyin"></span>
                <button @click="buyin='all'; filter()" class="hover:text-red-500 transition-colors">&times;</button>
            </span>
        </template>
    </div>

</div>

<script>
function wagerFilter() {
    return {
        query: '',
        status: 'all',
        privacy: 'all',
        buyin: 'all',

        init() {},

        filter() {
            const term = this.query.toLowerCase();
            let visible = 0;

            document.querySelectorAll('.wager-item').forEach(item => {
                const name    = (item.getAttribute('data-name') || '').toLowerCase();
                const creator = (item.getAttribute('data-creator') || '').toLowerCase();
                const iStatus  = item.getAttribute('data-status') || '';
                const iPrivacy = item.getAttribute('data-privacy') || '';
                const iBuyin  = parseInt(item.getAttribute('data-buyin') || '0', 10);

                const matchText    = !term || name.includes(term) || creator.includes(term);
                const matchStatus  = this.status === 'all' || iStatus === this.status;
                const matchPrivacy = this.privacy === 'all' || iPrivacy === this.privacy;
                const matchBuyin   = this.buyin === 'all'
                                  || (this.buyin === 'free' && iBuyin === 0)
                                  || (this.buyin === 'paid' && iBuyin > 0);

                const show = matchText && matchStatus && matchPrivacy && matchBuyin;
                item.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            const empty = document.querySelector('.no-wagers-message');
            if (empty) empty.style.display = visible ? 'none' : 'block';
        }
    };
}
</script>
