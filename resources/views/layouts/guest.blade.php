<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>The Wager</title>
    <script>
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
        .fade-up { animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
    </style>
</head>

<body class="font-sans antialiased min-h-screen">

    {{-- Light bg --}}
    <div class="dark:hidden fixed inset-0 bg-[#f0f2f5]"></div>
    <div class="dark:hidden fixed inset-0 grain opacity-40"></div>

    {{-- Dark bg --}}
    <div class="hidden dark:block fixed inset-0 bg-[#080b0f]">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-emerald-900/20 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 grain opacity-50"></div>
        {{-- Grid --}}
        <div class="absolute inset-0 opacity-[0.025]"
             style="background-image: linear-gradient(rgba(16,185,129,1) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,1) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center px-4 py-12">

        {{-- Logo --}}
        <div class="fade-up mb-8 text-center" style="animation-delay:0ms">
            <div class="flex items-center justify-center gap-2 mb-1">
                <div class="w-8 h-8 bg-emerald-500 rounded-xl rotate-12"></div>
                <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">TheWager</span>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-600 tracking-widest uppercase">Make it interesting</p>
        </div>

        {{-- Card --}}
        <div class="fade-up w-full max-w-md" style="animation-delay:100ms">
            <div class="bg-white/80 dark:bg-white/[0.03] backdrop-blur-sm border border-slate-200/80 dark:border-white/[0.07] rounded-2xl shadow-xl dark:shadow-none overflow-hidden">
                <div class="px-8 py-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>
</body>
</html>