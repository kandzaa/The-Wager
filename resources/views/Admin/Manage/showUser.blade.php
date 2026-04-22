<x-app-layout>
<div class="min-h-screen bg-[#080b0f] py-10">
    <div class="max-w-xl mx-auto px-4 sm:px-6">

        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin') }}" class="text-slate-500 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <span class="text-slate-600 text-sm">/</span>
            <span class="text-slate-400 text-sm">User</span>
        </div>

        @if(session('success'))
            <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Profile header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl font-black">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-0.5">Admin / User</p>
                <h1 class="text-2xl font-black tracking-tight text-white">{{ $user->name }}</h1>
                @if($user->banned_until && $user->banned_until > now())
                    <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/15 border border-red-500/20 text-red-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        Banned until {{ $user->banned_until->format('M d, Y H:i') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Info card --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden mb-4">
            <div class="divide-y divide-white/[0.04]">
                @foreach([
                    ['ID',      '#' . $user->id],
                    ['Email',   $user->email],
                    ['Role',    ucfirst($user->role)],
                    ['Balance', number_format($user->balance) . ' coins'],
                    ['Joined',  $user->created_at->format('Y-m-d H:i')],
                ] as [$label, $value])
                <div class="flex items-center justify-between px-6 py-3.5">
                    <span class="text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500">{{ $label }}</span>
                    <span class="text-sm text-white font-medium">{{ $value }}</span>
                </div>
                @endforeach
                @if($user->banned_until && $user->banned_until > now())
                <div class="flex items-center justify-between px-6 py-3.5">
                    <span class="text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-red-500">Ban reason</span>
                    <span class="text-sm text-red-400 font-medium">{{ $user->ban_reason ?: '—' }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
               class="flex-1 py-2.5 text-center text-sm font-semibold text-black bg-emerald-500 hover:bg-emerald-400 rounded-xl transition-all hover:-translate-y-px hover:shadow-[0_4px_16px_rgba(16,185,129,0.2)]">
                Edit User
            </a>
            <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST"
                  onsubmit="return confirm('Delete this user permanently?')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full py-2.5 text-sm font-semibold text-red-400 bg-red-500/[0.08] hover:bg-red-500/[0.15] border border-red-500/20 rounded-xl transition-all">
                    Delete User
                </button>
            </form>
        </div>

        {{-- Moderation panel --}}
        @if($user->id !== auth()->id())
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden" x-data="{ showBan: false }">
            <div class="px-5 py-3.5 border-b border-white/[0.05] flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-xs font-bold uppercase tracking-[0.15em] text-amber-500">Moderation</span>
            </div>

            <div class="divide-y divide-white/[0.04]">

                {{-- Zero balance --}}
                <div class="flex items-center justify-between px-5 py-4">
                    <div>
                        <p class="text-sm font-semibold text-white">Zero Balance</p>
                        <p class="text-xs text-slate-500 mt-0.5">Set their coin balance to 0</p>
                    </div>
                    <form action="{{ route('admin.Manage.users.zeroBalance', $user->id) }}" method="POST"
                          onsubmit="return confirm('Set {{ $user->name }}\'s balance to 0?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-xs font-bold text-amber-400 bg-amber-500/[0.08] hover:bg-amber-500/[0.15] border border-amber-500/20 rounded-lg transition-all">
                            Zero Balance
                        </button>
                    </form>
                </div>

                {{-- Ban / Unban --}}
                @if($user->banned_until && $user->banned_until > now())
                <div class="flex items-center justify-between px-5 py-4">
                    <div>
                        <p class="text-sm font-semibold text-white">Lift Ban</p>
                        <p class="text-xs text-slate-500 mt-0.5">Unban expires {{ $user->banned_until->diffForHumans() }}</p>
                    </div>
                    <form action="{{ route('admin.Manage.users.unban', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-xs font-bold text-emerald-400 bg-emerald-500/[0.08] hover:bg-emerald-500/[0.15] border border-emerald-500/20 rounded-lg transition-all">
                            Unban
                        </button>
                    </form>
                </div>
                @else
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm font-semibold text-white">Ban User</p>
                            <p class="text-xs text-slate-500 mt-0.5">Temporarily lock their account</p>
                        </div>
                        <button @click="showBan = !showBan" type="button"
                            class="px-4 py-2 text-xs font-bold text-red-400 bg-red-500/[0.08] hover:bg-red-500/[0.15] border border-red-500/20 rounded-lg transition-all">
                            Ban
                        </button>
                    </div>
                    <form x-show="showBan" x-cloak action="{{ route('admin.Manage.users.ban', $user->id) }}" method="POST"
                          class="space-y-3 pt-3 border-t border-white/[0.05]">
                        @csrf
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block text-[0.65rem] font-semibold uppercase tracking-[0.1em] text-slate-500 mb-1.5">Duration (days)</label>
                                <input type="number" name="duration" min="1" max="365" value="1" required
                                    class="w-full px-3 py-2 rounded-lg bg-black/30 border border-white/[0.08] text-white text-sm focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/20">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[0.65rem] font-semibold uppercase tracking-[0.1em] text-slate-500 mb-1.5">Reason (optional)</label>
                                <input type="text" name="reason" maxlength="500" placeholder="e.g. cheating"
                                    class="w-full px-3 py-2 rounded-lg bg-black/30 border border-white/[0.08] text-white text-sm placeholder-slate-600 focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/20">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-500 rounded-lg transition-all">
                            Confirm Ban
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
        @endif

    </div>
</div>
</x-app-layout>
