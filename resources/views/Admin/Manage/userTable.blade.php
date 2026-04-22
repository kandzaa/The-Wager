<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Management</p>
        <h2 class="text-xl font-black tracking-tight text-white">Users</h2>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-white/[0.06]">
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">ID</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Name</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Email</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3 hidden md:table-cell">Joined</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Balance</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Role</th>
                <th class="pb-3 text-left text-[0.65rem] font-semibold tracking-[0.15em] uppercase text-slate-500 px-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/[0.04]">
            @forelse ($users as $user)
                <tr class="group hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-3 text-slate-600 font-mono text-xs">#{{ $user->id }}</td>
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-white">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-3 text-slate-400">{{ $user->email }}</td>
                    <td class="py-3 px-3 text-slate-500 text-xs hidden md:table-cell">{{ $user->created_at?->diffForHumans() }}</td>
                    <td class="py-3 px-3">
                        <span class="text-emerald-400 font-semibold">{{ number_format($user->balance) }}</span>
                    </td>
                    <td class="py-3 px-3">
                        @if($user->role === 'admin')
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Admin</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">User</span>
                        @endif
                        @if($user->banned_until && $user->banned_until > now())
                            <span class="inline-flex items-center gap-1 ml-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                <span class="w-1 h-1 rounded-full bg-red-400 animate-pulse"></span>Banned
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-3" x-data="{ banOpen: false }">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.Manage.users.show', $user->id) }}"
                               class="p-1.5 rounded-lg text-slate-500 hover:text-blue-400 hover:bg-blue-500/[0.08] transition-all" title="View Profile">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
                               class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-400 hover:bg-emerald-500/[0.08] transition-all" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            {{-- Zero balance --}}
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.Manage.users.zeroBalance', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Zero {{ $user->name }}\'s balance?')">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/[0.08] transition-all" title="Zero Balance">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>

                            {{-- Ban / Unban --}}
                            @if($user->banned_until && $user->banned_until > now())
                                <form action="{{ route('admin.Manage.users.unban', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:text-emerald-400 hover:bg-emerald-500/[0.08] transition-all" title="Unban">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    </button>
                                </form>
                            @else
                                <button @click="banOpen = !banOpen" type="button" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-all" title="Ban">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            @endif
                            @endif

                            <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-all" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>

                        {{-- Inline ban form --}}
                        @if($user->id !== auth()->id())
                        <div x-show="banOpen" x-cloak class="mt-2 p-3 rounded-xl bg-red-500/[0.06] border border-red-500/20">
                            <form action="{{ route('admin.Manage.users.ban', $user->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="duration" min="1" max="365" value="1" placeholder="Days"
                                    class="w-16 px-2 py-1.5 rounded-lg bg-black/30 border border-white/[0.08] text-white text-xs focus:outline-none focus:border-red-500/50">
                                <input type="text" name="reason" placeholder="Reason (optional)"
                                    class="flex-1 px-2 py-1.5 rounded-lg bg-black/30 border border-white/[0.08] text-white text-xs placeholder-slate-600 focus:outline-none focus:border-red-500/50">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all">
                                    Ban
                                </button>
                                <button @click="banOpen = false" type="button" class="px-2 py-1.5 rounded-lg text-slate-500 hover:text-white text-xs transition-all">
                                    ✕
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-600 text-sm">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>