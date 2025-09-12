<div class="fixed inset-0 z-50 select-none">

    <div class="fixed inset-0 backdrop-blur-sm bg-slate-950/50" @click="showModal = false"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center  text-center sm:p-0 ">

            <div
                class="relative transform overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900 text-left shadow-2xl transition-all  sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto 
                ring-1 ring-slate-300 dark:ring-slate-700 backdrop-blur-sm ">

                <div class="bg-slate-50 dark:bg-slate-900 px-4 sm:pb-4 ">


                    @if (session('error'))
                        <div class="bg-rose-100 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-lg relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form :action="editId ? '{{ url('/wagers') }}/' + editId : '{{ route('wager.create') }}'"
                        method="POST" class="mt-4">
                        @csrf
                        <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="space-y-6">
                            <div>
                                <input type="text" name="name" placeholder="Theme" required x-model="form.name"
                                    class="p-3 mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors text-sm">
                            </div>

                            <div>
                                <textarea name="description" placeholder="Description" rows="3" x-model="form.description"
                                    class="p-3 mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors text-sm resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="number" name="max_players" placeholder="Max players" min="2"
                                        x-model="form.max_players" required
                                        class="p-3 mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors text-sm">
                                </div>
                            </div>

                            <div x-data="{ isPrivate: false }" x-init="isPrivate = form.visibility === 'private'"
                                class="flex items-center justify-between p-4 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                                <input type="hidden" name="visibility" :value="isPrivate ? 'private' : 'public'">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-sm font-medium text-slate-700 dark:text-slate-300">Visibility:</span>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <svg :class="{ 'text-emerald-600': !isPrivate, 'text-slate-400': isPrivate }"
                                                class="w-5 h-5 transition-colors" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                :class="{
                                                    'font-medium text-emerald-600 dark:text-emerald-400': !
                                                        isPrivate,
                                                    'text-slate-400': isPrivate
                                                }"
                                                class="text-sm transition-colors duration-200">
                                                Public
                                            </span>
                                        </div>

                                        <button @click.prevent="isPrivate = !isPrivate"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none "
                                            :class="{
                                                'bg-emerald-200 dark:bg-emerald-800': !
                                                    isPrivate,
                                                'bg-rose-200 dark:bg-rose-800': isPrivate
                                            }">
                                            <span class="sr-only">Toggle lobby visibility</span>
                                            <span aria-hidden="true"
                                                class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="{ 'translate-x-5': isPrivate, 'translate-x-0': !isPrivate }">
                                            </span>
                                        </button>

                                        <div class="flex items-center gap-2">
                                            <svg :class="{
                                                'text-rose-600 dark:text-rose-400': isPrivate,
                                                'text-slate-400': !
                                                    isPrivate
                                            }"
                                                class="w-5 h-5 transition-colors" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span
                                                :class="{
                                                    'font-medium text-rose-600 dark:text-rose-400': isPrivate,
                                                    'text-slate-400':
                                                        !isPrivate
                                                }"
                                                class="text-sm transition-colors duration-200">
                                                Private
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">Invite friends:
                                </div>
                                <div class="grid grid-cols-3 gap-3 max-h-32 overflow-y-auto">
                                    @foreach ($friends as $friend)
                                        <div class="p-3 border rounded-lg hover:shadow-sm transition-all duration-300 bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700"
                                            data-friend-id="{{ $friend->id }}">
                                            <div class="flex flex-col items-center text-center">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center shadow-md mb-2">
                                                    <span class="text-sm font-semibold text-white">
                                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <h3
                                                    class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate w-full">
                                                    {{ $friend->name }}
                                                </h3>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div x-data="{ choices: form.choices && form.choices.length ? [...form.choices] : [''] }" class="space-y-3">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300">Choices:</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="(choice, index) in choices" :key="index">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="'choices[]'" x-model="choices[index]"
                                                placeholder="Choice"
                                                class="p-3 block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors text-sm" />
                                            <button type="button" @click="choices.splice(index, 1)"
                                                x-show="choices.length > 1"
                                                class="px-2 py-2 text-rose-600 dark:text-rose-400 border border-rose-300 dark:border-rose-700 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors text-sm">×</button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="choices.push('')"
                                    class="px-4 py-2 text-sm text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">Add
                                    choice</button>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">End
                                    time:</label>
                                <div>
                                    <input type="datetime-local" name="ending_time" required
                                        x-model="form.ending_time_local"
                                        class="p-3 mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors text-sm">
                                </div>
                            </div>
                        </div>
                </div>

                <div
                    class="bg-slate-100 dark:bg-slate-800 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-300 dark:border-slate-700">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-lg bg-emerald-600 hover:bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors sm:ml-3 sm:w-auto">
                        <span x-text="editId ? 'Save Changes' : 'Create Wager'"></span>
                    </button>
                    </form>
                    <button type="button" @click="showModal = false"
                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-900 dark:text-slate-100 shadow-sm transition-colors sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
