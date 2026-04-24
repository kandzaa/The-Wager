<x-app-layout>
<div class="min-h-screen bg-slate-50 dark:bg-[#080b0f] py-10">
    <div class="max-w-xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <span class="text-slate-400 text-sm">/</span>
            <span class="text-slate-500 dark:text-slate-400 text-sm">Edit User</span>
        </div>

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Admin</p>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ $user->name }}</h1>
        </div>

        <div class="bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.06] rounded-2xl overflow-hidden">
            <form method="POST" action="{{ route('admin.Manage.users.update', $user->id) }}">
                @csrf @method('PUT')

                <div class="p-6 space-y-4">

                    {{-- Name --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20" />
                        @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20" />
                        @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Balance --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Balance</label>
                        <input type="number" name="balance" value="{{ old('balance', $user->balance) }}" required
                            min="0" max="2147483647"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20" />
                        @error('balance') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Role</label>
                        @if($user->id === auth()->id())
                            <div class="w-full h-11 px-4 bg-slate-100 dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] rounded-xl text-slate-500 text-sm flex items-center">
                                {{ ucfirst($user->role) }}
                            </div>
                            <input type="hidden" name="role" value="{{ $user->role }}" />
                            <p class="mt-1.5 text-xs text-slate-500">You cannot change your own role.</p>
                        @else
                        <div class="relative">
                            <select name="role"
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.08] rounded-xl text-slate-900 dark:text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 appearance-none cursor-pointer">
                                <option value="user"  {{ old('role', $user->role) === 'user'  ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @endif
                        @error('role') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/[0.05] flex items-center justify-end gap-3">
                    <a href="{{ route('admin') }}"
                        class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-white/[0.03] hover:bg-slate-100 dark:hover:bg-white/[0.06] border border-slate-200 dark:border-white/[0.07] rounded-xl transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-black bg-emerald-500 hover:bg-emerald-400 rounded-xl transition-all hover:-translate-y-px hover:shadow-[0_4px_16px_rgba(16,185,129,0.2)] active:translate-y-0">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
</x-app-layout>
