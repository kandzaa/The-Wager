<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>The Wager</title>
    <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=59840&format=png&color=000000">
    <script>
        // Apply theme immediately before paint to avoid flash
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .grain {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            pointer-events: none;
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="font-sans antialiased">

    {{-- Light mode bg --}}
    <div class="dark:hidden fixed inset-0 bg-[#f4f6f8] grain opacity-60 z-0"></div>

    {{-- Dark mode bg with orbs --}}
    <div class="hidden dark:block fixed inset-0 bg-[#080b0f] z-0">
        <div class="absolute top-0 left-1/3 w-[700px] h-[500px] bg-emerald-900/20 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-emerald-950/30 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 grain opacity-50"></div>
    </div>

    <div class="relative z-10 min-h-screen text-slate-800 dark:text-white">
        @include('layouts.sidebar')

        <div class="lg:pl-64 flex flex-col min-h-screen">
            {{-- Mobile header --}}
            <header class="flex items-center justify-between px-5 py-4 lg:hidden border-b border-slate-200/60 dark:border-white/[0.06] bg-white/70 dark:bg-white/[0.03] backdrop-blur-sm">
                <button @click.stop="sidebarOpen = !sidebarOpen" class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-black text-sm tracking-tight text-slate-900 dark:text-white">TheWager</span>
                <div class="w-6"></div>
            </header>

            @isset($header)
                <header class="px-6 py-5 border-b border-slate-200/60 dark:border-white/[0.06] bg-white/50 dark:bg-white/[0.02] backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto">{{ $header }}</div>
                </header>
            @endisset

            <main class="flex-1">{{ $slot }}</main>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>