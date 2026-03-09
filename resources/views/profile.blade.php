<x-app-layout>
<div class="select-none min-h-screen bg-[#080b0f] text-white relative overflow-hidden">

    {{-- Background atmosphere --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/3 w-[600px] h-[600px] bg-emerald-900/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-800/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E\"); opacity: 0.4;"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-6 py-16">

        {{-- Profile Header --}}
        <div class="mb-12">
            <div class="flex items-center gap-5 mb-2">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center text-2xl font-black text-white shadow-lg shadow-emerald-900/50">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-500 font-semibold mb-1">Account</p>
                    <h1 class="text-3xl font-black tracking-tight text-white">{{ Auth::user()->name }}</h1>
                </div>
            </div>
            <div class="mt-6 h-px bg-gradient-to-r from-emerald-500/40 via-emerald-500/10 to-transparent"></div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-emerald-900/40 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-red-900/40 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">

            {{-- Change Username --}}
            <div class="group rounded-2xl bg-white/[0.03] border border-white/[0.07] hover:border-emerald-500/30 transition-all duration-300 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">Username</p>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('profile.change-username') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', Auth::user()->name) }}"
                            class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/30 transition-all"
                        />
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-emerald-900/50 active:scale-95">
                            Save
                        </button>
                    </form>
                    @error('name')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Change Email --}}
            <div class="group rounded-2xl bg-white/[0.03] border border-white/[0.07] hover:border-emerald-500/30 transition-all duration-300 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">Email Address</p>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('profile.change-email') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', Auth::user()->email) }}"
                            class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/30 transition-all"
                        />
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-emerald-900/50 active:scale-95">
                            Save
                        </button>
                    </form>
                    @error('email')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="rounded-2xl bg-red-950/20 border border-red-500/10 hover:border-red-500/25 transition-all duration-300 overflow-hidden">
                <div class="px-6 py-5 border-b border-red-500/10">
                    <p class="text-xs uppercase tracking-[0.15em] text-red-500/70 font-semibold">Danger Zone</p>
                </div>
                <div class="px-6 py-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-300">Delete Account</p>
                        <p class="text-xs text-slate-600 mt-0.5">Permanently remove your account and all data.</p>
                    </div>
                    <button class="px-4 py-2 bg-red-950/60 hover:bg-red-900/60 border border-red-500/20 hover:border-red-500/40 text-red-400 text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                        Delete
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>