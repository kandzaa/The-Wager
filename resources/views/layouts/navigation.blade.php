<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=account_balance_wallet" />

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">



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
                            ? 'bg-amber-100 text-green-600'
                            : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        {{ Auth::user()->balance }} <span class="material-symbols-outlined">
                            account_balance_wallet
                        </span>
                    </a>

                    <a href="{{ route('friends') }}"
                        class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md
                        {{ request()->routeIs('friends')
                            ? 'bg-indigo-50 text-indigo-700'
                            : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        Friends
                    </a>

                    <div class="h-8 w-px bg-gray-200 mx-2"></div>

                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin') }}"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md
                            {{ request()->routeIs('admin')
                                ? 'bg-rose-50 text-rose-700'
                                : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                            Admin
                        </a>
                    @endif
                </div>




            </div>



        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

        </div>
    </div>
</nav>
