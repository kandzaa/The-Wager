<x-app-layout>
<div class="min-h-screen bg-[#080b0f] py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Header --}}
    <div class="fade-up">
        <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Control Panel</p>
        <h1 class="text-2xl font-black tracking-tight text-white">Admin Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">{{ now()->format('l, F j Y') }}</p>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 fade-up" style="animation-delay:40ms">

        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[0.6rem] uppercase tracking-[0.18em] text-slate-500 font-bold">Total Users</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-white tabular-nums">{{ number_format($stats['total_users']) }}</p>
                @if($stats['new_users_week'] > 0)
                <p class="text-xs text-emerald-400 mt-1 font-semibold">+{{ $stats['new_users_week'] }} this week</p>
                @else
                <p class="text-xs text-slate-600 mt-1">No new users this week</p>
                @endif
            </div>
        </div>

        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[0.6rem] uppercase tracking-[0.18em] text-slate-500 font-bold">Wagers</span>
                <div class="w-7 h-7 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-white tabular-nums">{{ number_format($stats['total_wagers']) }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    <span class="text-emerald-400 font-semibold">{{ $stats['active_wagers'] }} active</span>
                    · {{ $stats['ended_wagers'] }} ended
                </p>
            </div>
        </div>

        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[0.6rem] uppercase tracking-[0.18em] text-slate-500 font-bold">Total Pot</span>
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-white tabular-nums">{{ number_format($stats['total_pot']) }}</p>
                <p class="text-xs text-slate-500 mt-1">coins wagered total</p>
            </div>
        </div>

        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[0.6rem] uppercase tracking-[0.18em] text-slate-500 font-bold">Coins in Circ.</span>
                <div class="w-7 h-7 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-white tabular-nums">{{ number_format($stats['coins_in_circ']) }}</p>
                <p class="text-xs text-slate-500 mt-1">across all wallets</p>
            </div>
        </div>

    </div>

    {{-- ── Quick Actions ── --}}
    <div class="fade-up" style="animation-delay:80ms">
        <p class="text-[0.6rem] uppercase tracking-[0.2em] text-slate-600 font-bold mb-3">Quick Actions</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            <a href="{{ route('admin.Manage.users') }}"
               class="group bg-white/[0.02] hover:bg-white/[0.05] border border-white/[0.06] hover:border-emerald-500/20 rounded-2xl p-5 flex items-center gap-4 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0 group-hover:bg-emerald-500/15 transition-colors">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white">Manage Users</p>
                    <p class="text-xs text-slate-500">{{ number_format($stats['total_users']) }} registered</p>
                </div>
                <svg class="w-4 h-4 text-slate-700 group-hover:text-slate-400 ml-auto shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('admin.Manage.wagers') }}"
               class="group bg-white/[0.02] hover:bg-white/[0.05] border border-white/[0.06] hover:border-blue-500/20 rounded-2xl p-5 flex items-center gap-4 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0 group-hover:bg-blue-500/15 transition-colors">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white">Manage Wagers</p>
                    <p class="text-xs text-slate-500">{{ $stats['active_wagers'] }} active · {{ $stats['ended_wagers'] }} ended</p>
                </div>
                <svg class="w-4 h-4 text-slate-700 group-hover:text-slate-400 ml-auto shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('admin.Manage.customizations') }}"
               class="group bg-white/[0.02] hover:bg-white/[0.05] border border-white/[0.06] hover:border-violet-500/20 rounded-2xl p-5 flex items-center gap-4 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center shrink-0 group-hover:bg-violet-500/15 transition-colors">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white">Customizations</p>
                    <p class="text-xs text-slate-500">{{ number_format($stats['total_cosmetics']) }} items in shop</p>
                </div>
                <svg class="w-4 h-4 text-slate-700 group-hover:text-slate-400 ml-auto shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

        </div>
    </div>

    {{-- ── Recent Activity ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 fade-up" style="animation-delay:120ms">

        {{-- Recent Users --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Recent Users</p>
                <a href="{{ route('admin.Manage.users') }}" class="text-xs font-semibold text-emerald-500 hover:text-emerald-400 transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-white/[0.04]">
                @forelse($recentUsers as $u)
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.02] transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400/80 to-emerald-700/80 flex items-center justify-center text-white text-xs font-black shrink-0">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white truncate">{{ $u->name }}</p>
                        <p class="text-xs text-slate-600 truncate">{{ $u->email }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        @if($u->role === 'admin')
                        <span class="text-[0.6rem] px-1.5 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400 font-bold">admin</span>
                        @endif
                        <p class="text-[0.65rem] text-slate-600 mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-slate-600 text-center">No users yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Wagers --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Recent Wagers</p>
                <a href="{{ route('admin.Manage.wagers') }}" class="text-xs font-semibold text-emerald-500 hover:text-emerald-400 transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-white/[0.04]">
                @forelse($recentWagers as $w)
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.02] transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white truncate">{{ $w->name }}</p>
                        <p class="text-xs text-slate-600">by {{ optional($w->creator)->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        @if($w->status === 'active')
                        <span class="text-[0.6rem] px-1.5 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold">active</span>
                        @else
                        <span class="text-[0.6rem] px-1.5 py-0.5 rounded-md bg-slate-500/10 border border-slate-500/20 text-slate-500 font-bold">ended</span>
                        @endif
                        <p class="text-[0.65rem] text-slate-600 mt-0.5">{{ $w->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-slate-600 text-center">No wagers yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
</div>

<style>
.fade-up { animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>
