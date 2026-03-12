<x-app-layout>
<div class="min-h-screen bg-[#080b0f] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Page title --}}
        <div class="mb-2">
            <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Control Panel</p>
            <h1 class="text-2xl font-black tracking-tight text-white">Admin Dashboard</h1>
        </div>

        {{-- Users Section --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="p-6 sm:p-8">
                @include('Admin.Manage.userTable')
            </div>
        </div>

        {{-- Wagers Section --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="p-6 sm:p-8">
                @include('Admin.Manage.wagerTable')
            </div>
        </div>

    </div>
</div>
</x-app-layout>