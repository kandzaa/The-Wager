<x-app-layout>
<div class="min-h-screen bg-slate-50 dark:bg-[#080b0f] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin') }}"
               class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <span class="text-slate-400 text-sm">/</span>
            <span class="text-slate-500 dark:text-slate-400 text-sm">Users</span>
        </div>

        <div class="bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="p-6 sm:p-8">
                @include('Admin.Manage.userTable')
            </div>
        </div>

    </div>
</div>
</x-app-layout>