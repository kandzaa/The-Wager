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
                    </td>
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
                               class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-400 hover:bg-emerald-500/[0.08] transition-all" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-all" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
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