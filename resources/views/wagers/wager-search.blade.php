<div class="mb-8 fade-up" style="animation-delay:40ms">
    <div class="relative max-w-md">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text" id="wager-search-input"
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
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('wager-search-input');
    if (!input) return;

    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const term = input.value.toLowerCase();
            let visible = 0;
            document.querySelectorAll('.wager-item').forEach(item => {
                const name = item.getAttribute('data-name')?.toLowerCase() || '';
                const creator = item.getAttribute('data-creator')?.toLowerCase() || '';
                const show = !term || name.includes(term) || creator.includes(term);
                item.style.display = show ? 'block' : 'none';
                if (show) visible++;
            });
            const empty = document.querySelector('.no-wagers-message');
            if (empty) empty.style.display = visible ? 'none' : 'block';
        }, 300);
    });
});
</script>