<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/3 w-[600px] h-[500px] bg-emerald-900/15 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-6 py-16">

        {{-- Back --}}
        <div class="mb-8 fade-up">
            <a href="{{ route('friends') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Friends
            </a>
        </div>

        {{-- Profile card --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:80ms">

            {{-- Banner --}}
            <div class="h-24 bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-700 relative">
                <div class="absolute inset-0 opacity-20" style="background-image:url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E\")"></div>
            </div>

            {{-- Avatar overlapping banner --}}
            <div class="px-8 pb-8">
                <div class="-mt-10 mb-6 flex items-end justify-between">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center text-3xl font-black text-white shadow-xl ring-4 ring-white dark:ring-[#080b0f]">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="mb-1 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest">
                        Member
                    </div>
                </div>

                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white mb-1">{{ $user->name }}</h1>
                <p class="text-sm text-slate-500 mb-6">Joined {{ $user->created_at->diffForHumans() }}</p>

                <div class="h-px bg-slate-100 dark:bg-white/[0.06] mb-6"></div>

                {{-- Info rows --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.04]">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.15em] text-slate-400 dark:text-slate-600 font-semibold mb-0.5">Username</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.04]">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.15em] text-slate-400 dark:text-slate-600 font-semibold mb-0.5">Email</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>