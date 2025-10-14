<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Wager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981, #34d399, #6ee7b7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glow-effect {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.3);
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .particle {
            position: absolute;
            pointer-events: none;
            opacity: 0.6;
        }

        .particle:nth-child(1) {
            animation: particle-float-1 15s infinite linear;
        }

        .particle:nth-child(2) {
            animation: particle-float-2 20s infinite linear;
        }

        .particle:nth-child(3) {
            animation: particle-float-3 18s infinite linear;
        }

        .particle:nth-child(4) {
            animation: particle-float-4 22s infinite linear;
        }

        .particle:nth-child(5) {
            animation: particle-float-5 16s infinite linear;
        }

        @keyframes particle-float-1 {
            0% {
                transform: translateY(100vh) translateX(-100px) rotate(0deg);
            }

            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
            }
        }

        @keyframes particle-float-2 {
            0% {
                transform: translateY(100vh) translateX(100px) rotate(0deg);
            }

            100% {
                transform: translateY(-100px) translateX(-100px) rotate(-360deg);
            }
        }

        @keyframes particle-float-3 {
            0% {
                transform: translateY(100vh) translateX(50px) rotate(0deg);
            }

            100% {
                transform: translateY(-100px) translateX(-50px) rotate(360deg);
            }
        }

        @keyframes particle-float-4 {
            0% {
                transform: translateY(100vh) translateX(-50px) rotate(0deg);
            }

            100% {
                transform: translateY(-100px) translateX(150px) rotate(-360deg);
            }
        }

        @keyframes particle-float-5 {
            0% {
                transform: translateY(100vh) translateX(0px) rotate(0deg);
            }

            100% {
                transform: translateY(-100px) translateX(0px) rotate(360deg);
            }
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .stats-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }

        @keyframes pulse-glow {
            from {
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
            }

            to {
                box-shadow: 0 0 40px rgba(16, 185, 129, 0.6);
            }
        }
    </style>
</head>
<div
    class="select-none min-h-screen bg-gradient-to-br from-gray-900 via-black to-emerald-950 text-white overflow-hidden relative">

    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0"
            style="background-image: radial-gradient(circle at 2px 2px, rgba(16, 185, 129, 0.5) 1px, transparent 1px); background-size: 40px 40px;">
        </div>
    </div>

    <div class="absolute top-20 left-10 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-40 h-40 bg-emerald-600/20 rounded-full blur-3xl animate-pulse"
        style="animation-delay: 2s"></div>

    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-6 py-12">

        <div class="mb-12 animate-fade-in">
            <div
                class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl rotate-12 transform hover:rotate-0 transition-transform duration-300">
            </div>
        </div>

        <div class="text-center max-w-5xl mx-auto mb-12">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black mb-6 animate-slide-up">
                Make It
            </h1>

            <div class="text-5xl md:text-7xl lg:text-8xl font-black mb-8" id="animated-text">
                <span
                    class="inline-block bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-600 bg-clip-text text-transparent animate-gradient">
                    INTERESTING
                </span>
            </div>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto mb-12 animate-fade-in" style="animation-delay: 0.7s">
                Welcome to The Wager, where you can challange and bet against your friends.
            </p>
        </div>

        @auth
            <a href="{{ route('dashboard') }}" class="flex flex-col sm:flex-row gap-4 mb-16 animate-fade-in"
                style="animation-delay: 1s">
                <button
                    class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-semibold text-lg overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-emerald-500/25">
                    <span class="relative z-10">Dashboard</span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </button>
            </a>
        @else
            <div class="flex flex-col sm:flex-row gap-4 mb-16 animate-fade-in" style="animation-delay: 1s">
                <a href="{{ route('register') }}"
                    class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-semibold text-lg overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-emerald-500/25">
                    <span class="relative z-10">Get Started</span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </a>

                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-xl font-semibold text-lg border border-white/20 hover:bg-white/20 transform hover:scale-105 transition-all duration-300">
                    Sign In
                </a>
            </div>
        @endauth

    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes gradient {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 1s ease-out forwards;
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const text = 'INTERESTING';
        const element = document.querySelector('#animated-text span');
        let currentText = '';
        let index = 0;

        element.textContent = '';

        function typeWriter() {
            if (index < text.length) {
                currentText += text[index];
                element.textContent = currentText;
                index++;
                setTimeout(typeWriter, 150);
            }
        }

        setTimeout(typeWriter, 500);
    });
</script>

</html>
