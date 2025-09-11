<button onclick="toggleTheme()" id="theme-toggle"
    class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-gray-800 hover:text-gray-900 hover:bg-gray-50">
    🌙
</button>

<style>
    /* Dark theme styles */
    .dark-theme {
        color-scheme: dark;
    }

    .dark-theme nav {
        background: #1f2937 !important;
        border-color: #374151 !important;
        color: white !important;
    }

    .dark-theme nav a {
        color: #d1d5db !important;
    }

    .dark-theme nav a:hover {
        color: #f9fafb !important;
        background: #374151 !important;
    }

    .dark-theme #theme-toggle {
        color: #d1d5db !important;
        background: #374151 !important;
    }

    .dark-theme #theme-toggle:hover {
        color: #f9fafb !important;
        background: #4b5563 !important;
    }

    .dark-theme body {
        background: #111827 !important;
        color: #f9fafb !important;
    }

    .dark-theme .bg-white {
        background: #1f2937 !important;
    }

    .dark-theme .text-gray-800 {
        color: #f9fafb !important;
    }

    .dark-theme .text-gray-600 {
        color: #d1d5db !important;
    }
</style>

<script>
    function toggleTheme() {
        const body = document.body;
        const btn = document.getElementById('theme-toggle');

        if (body.classList.contains('dark-theme')) {
            body.classList.remove('dark-theme');
            btn.textContent = '🌙';
            localStorage.setItem('theme', 'light');
        } else {
            body.classList.add('dark-theme');
            btn.textContent = '☀️';
            localStorage.setItem('theme', 'dark');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.body.classList.add('dark-theme');
            document.getElementById('theme-toggle').textContent = '☀️';
        }
    });
</script>
