<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'The Wager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen">
    <section class="container mx-auto px-6 py-16 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-8">
            Make It <span class="text-green-500">Interesting</span>
        </h1>
        <p class="text-lg md:text-xl mb-12 text-gray-300 max-w-3xl mx-auto">
            Welcome to The Wager - where friendly competition meets excitement. Create or join wagers, compete with
            friends, and make every moment count.
        </p>
        @guest
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-bold text-lg">
                    Get Started
                </a>
                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-bold text-lg">
                    Sign In
                </a>
            </div>
        @else
            <a href="{{ url('/dashboard') }}"
                class="px-8 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-bold text-lg inline-block">
                Go to Dashboard
            </a>
        @endguest
    </section>

    {{-- <section class="container mx-auto px-6 py-16">
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="p-6 bg-gray-800 rounded-lg">
                <div class="text-green-500 text-4xl mb-4">🎲</div>
                <h3 class="text-xl font-bold mb-4">Create Wagers</h3>
                <p class="text-gray-400">Set up custom wagers and invite your friends to join the excitement.</p>
            </div>
            <div class="p-6 bg-gray-800 rounded-lg">
                <div class="text-green-500 text-4xl mb-4">🤝</div>
                <h3 class="text-xl font-bold mb-4">Compete</h3>
                <p class="text-gray-400">Challenge your friends in fair, transparent competitions.</p>
            </div>
            <div class="p-6 bg-gray-800 rounded-lg">
                <div class="text-green-500 text-4xl mb-4">🏆</div>
                <h3 class="text-xl font-bold mb-4">Win Big</h3>
                <p class="text-gray-400">Emerge victorious and claim your rewards.</p>
            </div>
        </div>
    </section> --}}
</body>

</html>
