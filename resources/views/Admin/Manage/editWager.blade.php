<x-app-layout>
<div class="min-h-screen bg-[#080b0f] py-10">
    <div class="max-w-xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin') }}" class="text-slate-500 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <span class="text-slate-600 text-sm">/</span>
            <span class="text-slate-400 text-sm">Edit Wager</span>
        </div>

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Admin</p>
            <h1 class="text-2xl font-black tracking-tight text-white">{{ $wager->name }}</h1>
        </div>

        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <form method="POST" action="{{ route('admin.Manage.wagers.update', $wager->id) }}">
                @csrf @method('PUT')

                <div class="p-6 space-y-4">

                    {{-- Name --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name', $wager->name) }}" required
                            class="w-full h-11 px-4 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)]" />
                        @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)] resize-none">{{ old('description', $wager->description) }}</textarea>
                        @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Max Players --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Max Players</label>
                        <input type="number" name="max_players" value="{{ old('max_players', $wager->max_players) }}" min="2" max="100" required
                            class="w-full h-11 px-4 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)]" />
                        @error('max_players') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Status</label>
                        <div class="relative">
                            <select name="status"
                                class="w-full h-11 px-4 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 appearance-none cursor-pointer">
                                <option value="public"  class="bg-[#0c0e12]" {{ old('status', $wager->status) === 'public'  ? 'selected' : '' }}>Public</option>
                                <option value="private" class="bg-[#0c0e12]" {{ old('status', $wager->status) === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('status') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ending Time --}}
                    <div>
                        <label class="block text-[0.65rem] font-semibold tracking-[0.12em] uppercase text-slate-500 mb-1.5">Ending Time</label>
                        <input type="datetime-local" name="ending_time"
                            value="{{ old('ending_time', \Carbon\Carbon::parse($wager->ending_time)->format('Y-m-d\TH:i')) }}"
                            required
                            class="w-full h-11 px-4 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all duration-200 focus:border-emerald-500/50 focus:bg-emerald-500/[0.02] focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)]" />
                        @error('ending_time') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-white/[0.02] border-t border-white/[0.05] flex items-center justify-end gap-3">
                    <a href="{{ route('admin') }}"
                        class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white bg-white/[0.03] hover:bg-white/[0.06] border border-white/[0.07] rounded-xl transition-all">
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