<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="text-base font-medium text-slate-900 dark:text-white">
            The Wager
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-500 dark:text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Rows -->
    <nav class="grid grid-cols-1 divide-y divide-slate-200 dark:divide-slate-800">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
            class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('wagers')" :active="request()->routeIs('wagers')"
            class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            {{ __('Wagers') }}
        </x-nav-link>

        <x-nav-link :href="route('balance')" :active="request()->routeIs('balance')"
            class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            {{ __('Balance') }}
        </x-nav-link>

        <x-nav-link :href="route('friends')" :active="request()->routeIs('friends')"
            class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            {{ __('Friends') }}
        </x-nav-link>


        <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')"
            class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            {{ __('Profile') }}
        </x-nav-link>

        @if (Auth::check() && Auth::user()->role === 'admin')
            <x-nav-link :href="route('admin')" :active="request()->routeIs('admin')"
                class="block px-6 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                {{ __('Admin') }}
            </x-nav-link>
        @endif
        <!-- Izrakstīšanās poga -->
        <div
            class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-slate-800 rounded-md transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </nav>
</aside>
