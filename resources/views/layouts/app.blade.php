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

<body x-data="{ sidebarOpen: false }"
    class="font-sans antialiased bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">

        @include('layouts.sidebar')

        <div class="lg:pl-64 flex flex-col flex-1">
            <header class="flex items-center justify-between p-4 lg:hidden">
                <button @click.stop="sidebarOpen = !sidebarOpen" class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                @include('components.theme-switch')
            </header>

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
    </div>
</body>

</html>
