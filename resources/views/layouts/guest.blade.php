<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ 'The Wager' }}</title>
    <script>
        (function() {
            const html = document.documentElement;
            const media = window.matchMedia('(prefers-color-scheme: dark)');

            function applyTheme() {
                const saved = localStorage.getItem('theme');
                const shouldDark = saved ? saved === 'dark' : media.matches;
                html.classList.toggle('dark', shouldDark);
            }

            applyTheme();

            media.addEventListener('change', () => {
                if (!localStorage.getItem('theme')) {
                    applyTheme();
                }
            });
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased text-slate-800 dark:text-slate-100 bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors duration-300">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-xl border border-slate-300/60 dark:border-slate-800 transition-colors duration-300">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
