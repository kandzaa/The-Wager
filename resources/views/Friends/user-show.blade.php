<x-app-layout>
    <x-slot name="header">
        <div
            class="bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-800 dark:to-emerald-900 rounded-xl p-6 shadow-lg">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                User Profile
            </h2>
        </div>
    </x-slot>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-3xl shadow-2xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">

                    <!-- Profile Header Section -->
                    <div
                        class="relative bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-800 dark:from-emerald-800 dark:via-emerald-900 dark:to-teal-900 p-8 pb-24">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <defs>
                                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor"
                                            stroke-width="0.5" />
                                    </pattern>
                                </defs>
                                <rect width="100" height="100" fill="url(#grid)" />
                            </svg>
                        </div>

                        <div
                            class="relative flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                            <div class="relative">
                                <div
                                    class="w-32 h-32 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-3xl shadow-2xl flex items-center justify-center border-4 border-white/30">
                                    <span class="text-6xl font-bold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="text-center md:text-left text-white flex-1">
                                <h1 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                                    {{ $user->name }}
                                </h1>
                                <div
                                    class="flex flex-col md:flex-row items-center md:items-start space-y-2 md:space-y-0 md:space-x-6 text-white/90">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        <span class="font-medium">{{ $user->email }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Member since {{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details Section -->
                    <div class="p-8 -mt-16 relative">
                        <div
                            class="bg-slate-50/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-700/60 p-8">

                            <!-- Section Header -->
                            <div class="mb-8 text-center">
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 dark:from-emerald-400 dark:to-emerald-500 bg-clip-text text-transparent mb-2">
                                    Profile Information
                                </h2>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Complete member details and account information
                                </p>
                            </div>

                            <!-- Info Cards Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                                <!-- Name Card -->
                                <div
                                    class="group relative overflow-hidden bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-2xl p-6 border border-slate-300/60 dark:border-slate-700 hover:shadow-lg hover:bg-white/80 dark:hover:bg-slate-800/60 hover:border-slate-400/60 dark:hover:border-slate-600 transition-all duration-300">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-full -mr-10 -mt-10">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-10 h-10 bg-emerald-500 dark:bg-emerald-600 rounded-xl flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <label
                                                class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">
                                                Full Name
                                            </label>
                                        </div>
                                        <p class="text-xl font-bold text-slate-800 dark:text-slate-100">
                                            {{ $user->name }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Email Card -->
                                <div
                                    class="group relative overflow-hidden bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-2xl p-6 border border-slate-300/60 dark:border-slate-700 hover:shadow-lg hover:bg-white/80 dark:hover:bg-slate-800/60 hover:border-slate-400/60 dark:hover:border-slate-600 transition-all duration-300">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-slate-500/10 rounded-full -mr-10 -mt-10">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-10 h-10 bg-slate-500 dark:bg-slate-600 rounded-xl flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                </svg>
                                            </div>
                                            <label
                                                class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                                Email Address
                                            </label>
                                        </div>
                                        <p class="text-xl font-bold text-slate-800 dark:text-slate-100 break-all">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div
                                class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 pt-8 border-t border-slate-300 dark:border-slate-700">
                                <a href="{{ route('friends') }}"
                                    class="group flex items-center px-8 py-4 bg-slate-600 hover:bg-slate-500 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5 mr-3 group-hover:-translate-x-1 transition-transform duration-200"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Back to Friends
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shareProfile() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $user->name }} - Profile',
                    text: 'Check out {{ $user->name }}\'s profile',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const button = event.target.closest('button');
                    const originalText = button.innerHTML;
                    button.innerHTML = `
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Profile Link Copied!`;

                    setTimeout(() => {
                        button.innerHTML = originalText;
                    }, 2000);
                }).catch(() => {
                    alert('Unable to copy link');
                });
            }
        }
    </script>
</x-app-layout>
