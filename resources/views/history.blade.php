<x-app-layout>
<div class="select-none min-h-screen bg-[#080b0f] text-white relative overflow-hidden">

    {{-- Background atmosphere --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-1/3 w-[700px] h-[500px] bg-emerald-900/15 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-1/4 left-0 w-[400px] h-[400px] bg-emerald-950/30 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E\"); opacity: 0.4;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-16">

        {{-- Page Header --}}
        <div class="mb-12">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-500 font-semibold mb-2">Records</p>
            <h1 class="text-4xl font-black tracking-tight text-white">Wager History</h1>
            <div class="mt-4 h-px bg-gradient-to-r from-emerald-500/40 via-emerald-500/10 to-transparent"></div>
        </div>

        {{-- Your Wagers --}}
        <section class="mb-16">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-400">Your Wagers</h2>
                @if($userWagers->isNotEmpty())
                    <span class="ml-auto px-2.5 py-0.5 bg-emerald-900/40 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-full">
                        {{ $userWagers->total() }}
                    </span>
                @endif
            </div>

            @if ($userWagers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach ($userWagers as $wager)
                        <a href="{{ route('history.show', $wager) }}" class="group block">
                            <div class="h-full rounded-2xl bg-white/[0.03] border border-white/[0.07] group-hover:border-emerald-500/40 group-hover:bg-white/[0.05] transition-all duration-300 p-5 relative overflow-hidden">
                                {{-- Hover glow --}}
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-500/0 group-hover:from-emerald-500/5 group-hover:to-transparent transition-all duration-500 rounded-2xl"></div>

                                <div class="relative">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="font-bold text-white group-hover:text-emerald-300 transition-colors duration-200 leading-tight pr-3">
                                            {{ $wager->name }}
                                        </h3>
                                        <span class="shrink-0 px-2 py-0.5 text-xs rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                            Ended
                                        </span>
                                    </div>

                                    @if ($wager->description)
                                        <p class="text-sm text-slate-500 mb-4 line-clamp-2 leading-relaxed">{{ $wager->description }}</p>
                                    @endif

                                    <div class="pt-3 border-t border-white/[0.05] flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded-full bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center text-[9px] font-bold text-slate-300">
                                                {{ strtoupper(substr($wager->creator->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-slate-500">{{ $wager->creator->name ?? 'Unknown' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $wager->players_count }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-slate-600 text-sm">{{ $userWagers->links() }}</div>
            @else
                <div class="rounded-2xl bg-white/[0.02] border border-white/[0.05] p-10 text-center">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-slate-800/60 flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-slate-500 text-sm">No wagers in your history yet.</p>
                </div>
            @endif
        </section>

        {{-- Public Wagers --}}
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-5 bg-slate-600 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-400">Public Wagers</h2>
                @if($publicWagers->isNotEmpty())
                    <span class="ml-auto px-2.5 py-0.5 bg-slate-800/60 border border-slate-700/50 text-slate-400 text-xs font-semibold rounded-full">
                        {{ $publicWagers->total() }}
                    </span>
                @endif
            </div>

            @if ($publicWagers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach ($publicWagers as $wager)
                        <a href="{{ route('history.show', $wager) }}" class="group block">
                            <div class="h-full rounded-2xl bg-white/[0.02] border border-white/[0.05] group-hover:border-slate-500/40 group-hover:bg-white/[0.04] transition-all duration-300 p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-bold text-slate-200 group-hover:text-white transition-colors duration-200 leading-tight pr-3">
                                        {{ $wager->name }}
                                    </h3>
                                    <span class="shrink-0 px-2 py-0.5 text-xs rounded-full bg-slate-800 text-slate-500 border border-slate-700">
                                        Ended
                                    </span>
                                </div>

                                @if ($wager->description)
                                    <p class="text-sm text-slate-600 mb-4 line-clamp-2 leading-relaxed">{{ $wager->description }}</p>
                                @endif

                                <div class="pt-3 border-t border-white/[0.04] flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-[9px] font-bold text-slate-400">
                                            {{ strtoupper(substr($wager->creator->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-slate-600">{{ $wager->creator->name ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $wager->players_count }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-slate-600 text-sm">{{ $publicWagers->links() }}</div>
            @else
                <div class="rounded-2xl bg-white/[0.02] border border-white/[0.05] p-10 text-center">
                    <p class="text-slate-600 text-sm">No public wagers available.</p>
                </div>
            @endif
        </section>

    </div>
</div>
</x-app-layout>