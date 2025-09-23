<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <x-nav-link href="{{ route('admin') }}">Admin</x-nav-link>
        <x-nav-link href="{{ route('statistics') }}">Statistics</x-nav-link>

    </div>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        @include('Admin.Manage.userTable')
                        @include('Admin.Manage.wagerTable')

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
