<aside
    class="select-none fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="text-base font-medium text-slate-900 dark:text-white">
            The Wager
        </a>
        @include('components.theme-switch')
    </div>

    <!-- Navigation Rows -->
    <nav class="grid grid-cols-1 divide-y divide-slate-200 dark:divide-slate-800">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="home-outline" class="size-5 mr-2"></ion-icon>
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('wagers.index')" :active="request()->routeIs('wagers.index')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="game-controller-outline" class="size-5 mr-2"></ion-icon>
            {{ __('Wagers') }}
        </x-nav-link>

        <x-nav-link :href="route('balance')" :active="request()->routeIs('balance')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="wallet-outline" class="size-5 mr-2"></ion-icon>
            {{ __('Balance') }}
        </x-nav-link>

        <x-nav-link :href="route('friends')" :active="request()->routeIs('friends')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="people-outline" class="size-5 mr-2"></ion-icon>
            {{ __('Friends') }}
        </x-nav-link>


        <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="person-circle-outline" class="size-5 mr-2"></ion-icon>
            {{ __('Profile') }}
        </x-nav-link>

        <x-nav-link :href="route('history')" :active="request()->routeIs('history')"
            class="block px-4 py-4 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
            <ion-icon name="hourglass-outline" class="size-5 mr-2"></ion-icon>
            {{ __('History') }}
        </x-nav-link>



        @if (Auth::check() && Auth::user()->role === 'admin')
            <div class="block px-4 py-3 text-sm text-slate-600 dark:text-slate-600">
                {{ __('Admin') }}
            </div>

            <x-nav-link :href="route('admin')" :active="request()->routeIs('admin')"
                class="block px-4 py-4 text-sm  text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                <ion-icon name="construct-outline" class="size-5 mr-2"></ion-icon>
                {{ __('Manage') }}
            </x-nav-link>
        @endif
        <div
            class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-slate-800 rounded-md transition-colors duration-150">
                    <ion-icon name="log-out-outline" class="size-5 mr-2"></ion-icon>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </nav>
</aside>
