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

            // Initial apply before paint
            applyTheme();

            // React to system theme changes when no explicit choice is stored
            media.addEventListener('change', () => {
                if (!localStorage.getItem('theme')) {
                    applyTheme();
                }
            });
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        @include('layouts.navigation')

        @isset($header)
            <header
                class="bg-white/80 dark:bg-slate-900/40 backdrop-blur-sm shadow-sm border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
