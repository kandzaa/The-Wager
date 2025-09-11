<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-xl border border-slate-300/60 dark:border-slate-800">
                    <div class="p-8">
                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Current
                                        Balance</h3>
                                    <div class="flex items-center space-x-3">
                                        <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ Auth::user()->balance }}
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-300 text-lg">Coins</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
