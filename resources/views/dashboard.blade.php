<x-app-layout>
    <x-slot name="header">
        <div
            class="select-none bg-gradient-to-r from-slate-200 to-slate-300 dark:from-gray-600 dark:to-gray-700 rounded-xl p-6 shadow-lg">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                {{ 'Hello, ' . Auth::user()->name }}!
            </h2>
        </div>
    </x-slot>

    <div
        class="select-none min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700">
                            <div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Total Users
                                    </h3>
                                    <div
                                        class="flex items-center gap-2 text-2xl font-bold text-slate-900 dark:text-slate-100">
                                        <ion-icon name="people-circle"></ion-icon>
                                        {{ $usersCount ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>

                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Total
                                        Wagers
                                    </h3>
                                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                                        {{ $wagersCount ?? 0 }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
