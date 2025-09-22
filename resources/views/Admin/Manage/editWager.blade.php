<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Edit Wager</h2>
                        </div>


                        <form method="POST" action="{{ route('admin.Manage.wagers.update', $wager->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- derību vārds -->
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full px-3 py-2" type="text"
                                    name="name" :value="old('name', $wager->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- apraksts -->
                            <div class="mb-4">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full mt-1 px-3 py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $wager->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Max spēlētāji -->
                            <div class="mb-4">
                                <x-input-label for="max_players" :value="__('Maximum Players')" />
                                <x-text-input id="max_players" class="block mt-1 w-full px-3 py-2" type="number"
                                    name="max_players" :value="old('max_players', $wager->max_players)" min="2" max="100" required />
                                <x-input-error :messages="$errors->get('max_players')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="block w-full mt-1 px-3 py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="public"
                                        {{ old('status', $wager->status) === 'public' ? 'selected' : '' }}>Public
                                    </option>
                                    <option value="private"
                                        {{ old('status', $wager->status) === 'private' ? 'selected' : '' }}>Private
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>


                            <!-- Ending Time -->
                            <div class="mb-4">
                                <x-input-label for="ending_time" :value="__('Ending Time')" />
                                <x-text-input id="ending_time" class="block mt-1 w-full px-3 py-2" type="datetime-local"
                                    name="ending_time"
                                    value="{{ old('ending_time', \Carbon\Carbon::parse($wager->ending_time)->format('Y-m-d\TH:i')) }}"
                                    required />
                                <x-input-error :messages="$errors->get('ending_time')" class="mt-2" />
                            </div>


                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('admin') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Cancel') }}
                                </a>
                                <x-primary-button class="ml-4">
                                    {{ __('Update Wager') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
