<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Edit User</h2>
                        </div>
                        <form method="POST" action="{{ route('admin.Manage.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    value="{{ $user->name }}" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    value="{{ $user->email }}" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="balance" :value="__('Balance')" />
                                <x-text-input id="balance" class="block mt-1 w-full" type="number" name="balance"
                                    value="{{ $user->balance }}" required />
                                <x-input-error :messages="$errors->get('balance')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" class="block mt-1 w-full" name="role">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                </select>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <a href="{{ route('admin') }}"
                                    class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-500 transition">Cancel</a>
                                <button type="submit"
                                    class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-500 transition">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
