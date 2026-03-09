<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Wager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }

        .grain {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .reveal { animation: reveal 0.8s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes reveal {
            from { opacity:0; transform:translateY(32px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .ticker-wrap { overflow: hidden; }
        .ticker {
            display: flex;
            gap: 3rem;
            animation: ticker 20s linear infinite;
            white-space: nowrap;
        }
        @keyframes ticker {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            background: #10b981;
            transition: all 0.3s;
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
        }
        .btn-primary:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 20px 40px rgba(16,185,129,0.3); }

        .glow-line {
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            height: 1px;
        }
    </style>
</head>
<body class="bg-[#080b0f] text-white min-h-screen overflow-x-hidden">

    {{-- Noise overlay --}}
    <div class="fixed inset-0 grain pointer-events-none z-10"></div>

    {{-- Glowing orbs --}}
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-emerald-700/15 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-emerald-900/20 rounded-full blur-[100px]"></div>
        <div class="absolute top-1/3 right-0 w-[300px] h-[300px] bg-emerald-800/10 rounded-full blur-[80px]"></div>
    </div>

    {{-- Grid pattern --}}
    <div class="fixed inset-0 pointer-events-none z-0 opacity-[0.03]"
         style="background-image: linear-gradient(rgba(16,185,129,1) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,1) 1px, transparent 1px); background-size: 60px 60px;">
    </div>

    {{-- Nav --}}
    <nav class="relative z-20 flex items-center justify-between px-8 py-6 max-w-7xl mx-auto reveal" style="animation-delay:0ms">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-emerald-500 rounded-lg rotate-12"></div>
            <span class="font-display font-800 text-lg tracking-tight">TheWager</span>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white transition">Sign in</a>
                <a href="{{ route('register') }}" class="btn-primary px-5 py-2 text-sm font-semibold text-white rounded-xl">Get started</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <main class="relative z-20 max-w-7xl mx-auto px-8 pt-24 pb-32 text-center">

        <div class="reveal" style="animation-delay:100ms">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-900/40 border border-emerald-500/20 text-emerald-400 text-xs font-semibold uppercase tracking-widest mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Bets are live
            </span>
        </div>

        <h1 class="font-display font-black text-[clamp(3rem,10vw,8rem)] leading-none tracking-tight mb-6 reveal" style="animation-delay:200ms">
            Make It<br>
            <span class="relative inline-block">
                <span class="bg-gradient-to-r from-emerald-300 via-emerald-400 to-emerald-500 bg-clip-text text-transparent" id="hero-word">INTERESTING</span>
                <span class="absolute -bottom-2 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-500 to-transparent"></span>
            </span>
        </h1>

        <p class="text-slate-400 text-lg max-w-xl mx-auto mb-12 leading-relaxed reveal" style="animation-delay:300ms">
            Challenge your friends. Put something on the line.<br>Make every prediction count.
        </p>

        <div class="flex items-center justify-center gap-4 reveal" style="animation-delay:400ms">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary px-8 py-4 text-base font-bold text-white rounded-2xl">
                    Go to Dashboard →
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-base font-bold text-white rounded-2xl">
                    Start wagering →
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 text-base font-semibold text-slate-300 hover:text-white bg-white/[0.05] hover:bg-white/[0.08] border border-white/10 rounded-2xl transition-all duration-300">
                    Sign in
                </a>
            @endauth
        </div>

       
    </main>

    {{-- Scrolling ticker --}}
    <div class="relative z-20 border-y border-white/[0.05] py-4 reveal" style="animation-delay:600ms">
        <div class="ticker-wrap">
            <div class="ticker">
                @foreach(range(1,16) as $i)
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">
                        {{ $i % 4 === 0 ? '✦ THE WAGER' : ($i % 4 === 1 ? '✦ PLACE YOUR BET' : ($i % 4 === 2 ? '✦ WIN BIG' : '✦ CHALLENGE FRIENDS')) }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>