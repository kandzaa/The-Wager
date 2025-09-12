<button onclick="toggleTheme()" id="theme-toggle"
    class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800">
    <span id="theme-icon">🌙</span>
</button>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const btn = document.getElementById('theme-icon');

        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            btn.textContent = '🌙';
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            btn.textContent = '☀️';
            localStorage.setItem('theme', 'dark');
        }
    }

    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const html = document.documentElement;
        const btn = document.getElementById('theme-icon');

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            html.classList.add('dark');
            if (btn) btn.textContent = '☀️';
        } else {
            html.classList.remove('dark');
            if (btn) btn.textContent = '🌙';
        }
    });
</script>
