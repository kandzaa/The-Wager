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

        {{-- Profile header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl font-black">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-0.5">Admin / User</p>
                <h1 class="text-2xl font-black tracking-tight text-white">{{ $user->name }}</h1>
            </div>
        </div>

        {{-- Info card --}}
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden mb-4">
            <div class="divide-y divide-white/[0.04]">
                @foreach([
                    ['ID',      '#' . $user->id],
                    ['Email',   $user->email],
                    ['Role',    ucfirst($user->role)],
                    ['Balance', number_format($user->balance)],
                    ['Joined',  $user->created_at->format('Y-m-d H:i')],
                ] as [$label, $value])
                <div class="flex items-center justify-between px-6 py-3.5">
                    <span class="text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500">{{ $label }}</span>
                    <span class="text-sm text-white font-medium">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
               class="flex-1 py-2.5 text-center text-sm font-semibold text-black bg-emerald-500 hover:bg-emerald-400 rounded-xl transition-all hover:-translate-y-px hover:shadow-[0_4px_16px_rgba(16,185,129,0.2)]">
                Edit User
            </a>
            <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST"
                  onsubmit="return confirm('Delete this user?')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full py-2.5 text-sm font-semibold text-red-400 bg-red-500/[0.08] hover:bg-red-500/[0.15] border border-red-500/20 rounded-xl transition-all">
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>
</x-app-layout>