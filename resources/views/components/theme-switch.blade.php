<div x-data="{
    isDark: false,
    init() {
        const html = document.documentElement;
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        // Derive initial state from DOM or storage/system
        this.isDark = html.classList.contains('dark') || saved === 'dark' || (!saved && prefersDark.matches);
        this.syncDom();
        // Update when system preference changes and user hasn't made an explicit choice
        prefersDark.addEventListener('change', (e) => {
            const explicit = localStorage.getItem('theme');
            if (!explicit) {
                this.isDark = e.matches;
                this.syncDom();
            }
        });
    },
    toggle() {
        this.isDark = !this.isDark;
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        this.syncDom();
    },
    syncDom() {
        document.documentElement.classList.toggle('dark', this.isDark);
    }
}" x-init="init()">
    <button @click="toggle()" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" id="theme-toggle"
        class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800">
        <span x-show="!isDark" class="inline-block" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" />
                <path fill-rule="evenodd"
                    d="M12 2.25a.75.75 0 0 1 .75.75v2a.75.75 0 0 1-1.5 0v-2A.75.75 0 0 1 12 2.25Zm0 16a.75.75 0 0 1 .75.75v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 1 .75-.75Zm9-6a.75.75 0 0 1-.75.75h-2a.75.75 0 0 1 0-1.5h2A.75.75 0 0 1 21 12Zm-16 0a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h2A.75.75 0 0 1 5 12Zm12.78 6.03a.75.75 0 0 1-1.06 1.06l-1.414-1.414a.75.75 0 0 1 1.06-1.06L17.78 18.03Zm-9.9-9.9a.75.75 0 0 1-1.06 1.06L5.406 9.78a.75.75 0 0 1 1.06-1.06l1.414 1.414Zm9.9-1.06a.75.75 0 0 1 0 1.06L16.366 9.78a.75.75 0 0 1-1.06-1.06l1.414-1.414Zm-9.9 9.9a.75.75 0 1 1-1.06 1.06L5.406 18.03a.75.75 0 0 1 1.06-1.06l1.414 1.414Z"
                    clip-rule="evenodd" />
            </svg>
        </span>
        <span x-show="isDark" class="inline-block" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path
                    d="M21.752 15.002A9.718 9.718 0 0 1 12 21.75 9.75 9.75 0 0 1 8.998 2.248a.75.75 0 0 1 .955.955 8.25 8.25 0 0 0 10.844 10.844.75.75 0 0 1 .955.955Z" />
            </svg>
        </span>
    </button>
</div>

<script>
    if (!window.Alpine) {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldDark = document.documentElement.classList.contains('dark') || saved === 'dark' || (!saved &&
            prefersDark);
        document.documentElement.classList.toggle('dark', shouldDark);
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('theme-toggle');
            if (btn) {
                btn.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }
        });
    }
</script>
