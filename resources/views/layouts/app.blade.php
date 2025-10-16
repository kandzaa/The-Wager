<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ 'The Wager' }}</title>
    <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=59840&format=png&color=000000">
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

<body x-data="{ sidebarOpen: false }"
    class="font-sans antialiased bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        @include('layouts.sidebar')
        <div class="lg:pl-64 flex flex-col flex-1">
            <header class="flex items-center justify-between p-4 lg:hidden">
                <button @click.stop="sidebarOpen = !sidebarOpen"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
