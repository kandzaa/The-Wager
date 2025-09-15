import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Ensure theme stays in sync across tabs and after client-side navigations
(() => {
    const media = window.matchMedia('(prefers-color-scheme: dark)');

    function applyThemeFromState() {
        const saved = localStorage.getItem('theme');
        const shouldDark = saved ? saved === 'dark' : media.matches;
        document.documentElement.classList.toggle('dark', shouldDark);
    }

    // Apply on JS bootstrap (useful for client-side navigations)
    applyThemeFromState();

    // Respond when theme changes in another tab/window
    window.addEventListener('storage', (e) => {
        if (e.key === 'theme') {
            applyThemeFromState();
        }
    });

    // If system theme changes and the user hasn't explicitly chosen, update
    media.addEventListener('change', () => {
        if (!localStorage.getItem('theme')) {
            applyThemeFromState();
        }
    });
})();
