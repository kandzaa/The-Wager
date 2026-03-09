<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute -top-20 left-1/4 w-[800px] h-[500px] bg-emerald-900/20 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-950/30 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-14">

        {{-- Greeting --}}
        <div class="mb-12 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-1">Welcome back</p>
            <h1 class="text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                {{ Auth::user()->name }}<span class="text-emerald-500">.</span>
            </h1>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            @php
                $statCards = [
                    ['label' => 'Total Users',  'value' => $usersCount ?? 0,  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'Total Wagers', 'value' => $wagersCount ?? 0, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ];
            @endphp
            @foreach($statCards as $i => $card)
            <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-5 hover:border-emerald-400 dark:hover:border-emerald-500/30 transition-all duration-300 shadow-sm dark:shadow-none" style="animation-delay:{{ ($i+1)*80 }}ms">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Pending Invitations --}}
        @if(isset($pendingInvitations) && $pendingInvitations->isNotEmpty())
        <div class="fade-up mb-8" style="animation-delay:240ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-amber-400 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Pending Invitations</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full">{{ $pendingInvitations->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($pendingInvitations as $invitation)
                <div class="rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/10 hover:border-amber-400 dark:hover:border-amber-500/25 transition-all duration-300 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $invitation->wager->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                From <span class="text-slate-700 dark:text-slate-400">{{ $invitation->wager->creator->name }}</span>
                                · Expires {{ $invitation->expires_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('invitations.accept', $invitation->token) }}"
                               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Accept
                            </a>
                            <a href="{{ route('invitations.decline', $invitation->token) }}"
                               class="px-4 py-2 bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-300 dark:border-white/[0.08] text-slate-600 dark:text-slate-400 text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Decline
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Active Wagers --}}
        @if(isset($joinedWagers) && $joinedWagers->isNotEmpty())
        <div class="fade-up" style="animation-delay:320ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Active Wagers</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full">{{ $joinedWagers->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($joinedWagers as $player)
                    @php $wager = $player->wager; @endphp
                    <a href="{{ route('wagers.show', $wager) }}" class="group block">
                        <div class="rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] group-hover:border-emerald-400 dark:group-hover:border-emerald-500/40 transition-all duration-300 p-5 shadow-sm dark:shadow-none">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors truncate">{{ $wager->name }}</h4>
                                    <div class="flex items-center gap-4 mt-1.5 flex-wrap">
                                        <span class="text-xs text-slate-600 dark:text-slate-400">{{ $wager->creator->name }}</span>
                                        <span class="text-xs text-slate-300 dark:text-slate-600">·</span>
                                        <span class="text-xs text-slate-500">{{ $wager->players_count }}/{{ $wager->max_players }} players</span>
                                        <span class="text-xs text-slate-300 dark:text-slate-600">·</span>
                                        <span class="text-xs text-slate-500">Ends {{ $wager->ending_time->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-600 group-hover:border-emerald-500 transition-all duration-300">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Empty state --}}
        @if((!isset($joinedWagers) || $joinedWagers->isEmpty()) && (!isset($pendingInvitations) || $pendingInvitations->isEmpty()))
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] p-16 text-center shadow-sm dark:shadow-none" style="animation-delay:240ms">
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-slate-700 dark:text-slate-400 font-semibold mb-1">Nothing going on yet</p>
            <p class="text-slate-500 text-sm">Join a wager or create one to get started.</p>
        </div>
        @endif

    </div>
</div>
<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>