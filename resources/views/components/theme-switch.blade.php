<button id="theme-toggle" aria-label="Toggle dark mode"
    class="p-2 rounded-md transition-colors duration-200 text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800">
    <ion-icon name="sunny" id="light-icon" class="w-6 h-6"></ion-icon>
    <ion-icon name="moon" id="dark-icon" class="w-6 h-6" style="display:none"></ion-icon>
</button>

<script>
    // Set initial theme
    const lightIcon = document.getElementById('light-icon');
    const darkIcon = document.getElementById('dark-icon');

    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        lightIcon.style.display = 'none';
        darkIcon.style.display = 'block';
    } else {
        document.documentElement.classList.remove('dark');
        lightIcon.style.display = 'block';
        darkIcon.style.display = 'none';
    }

    // Toggle theme on button click
    document.getElementById('theme-toggle').addEventListener('click', function() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.theme = isDark ? 'dark' : 'light';

        // Toggle icons
        lightIcon.style.display = isDark ? 'none' : 'block';
        darkIcon.style.display = isDark ? 'block' : 'none';

        // Update button text
        this.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    });
</script>
