<nav
    class="bg-gray-50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 transition-colors duration-300 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex items-center gap-2">
                    @include('components.theme-switch')
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('wagers')" :active="request()->routeIs('wagers')">
                        {{ __('Wagers') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <div class="flex space-x-1">
                    <a href="{{ route('balance') }}"
                        class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md flex items-center gap-1
                        {{ request()->routeIs('balance')
                            ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                            : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800 border border-transparent' }}">
                        Balance
                    </a>

                    <a href="{{ route('friends') }}"
                        class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md
                        {{ request()->routeIs('friends')
                            ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                            : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800 border border-transparent' }}">
                        Friends
                    </a>

                    <div class="h-8 w-px bg-slate-300 dark:bg-slate-700 mx-2"></div>

                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin') }}"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md
                            {{ request()->routeIs('admin')
                                ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800'
                                : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-slate-100 dark:hover:bg-slate-800 border border-transparent' }}">
                            Admin
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'border-emerald-500 text-slate-900 dark:text-slate-100 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                {{ __('Dashboard') }}
            </a>
        </div>

        <div class="pt-4 pb-1 border-t border-slate-200 dark:border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</nav>
