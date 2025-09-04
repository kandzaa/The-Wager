<div class="fixed inset-0 z-50 select-none">

    <div class="fixed inset-0 backdrop-blur-sm bg-black/30" @click="showModal = false"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg
                ring-1 ring-gray-300 ring-opacity-50">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 tracking-tight">Create Wager</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    `
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    <form action="{{ route('wager.create') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <input type="text" name="name" placeholder="Name" required
                                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div>
                                <textarea name="description" placeholder="Description" rows="3"
                                    class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="number" name="max_players" placeholder="Max players" min="2"
                                        required
                                        class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <input type="number" name="entry_fee" placeholder="Entry fee (optional)"
                                        min="0" required
                                        class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>

                            <div x-data="{ isPrivate: false }" class="flex items-center justify-between p-2 rounded-lg">
                                <input type="hidden" name="visibility" :value="isPrivate ? 'private' : 'public'">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700">Visibility:</span>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <svg :class="{ 'text-green-600': !isPrivate, 'text-gray-400': isPrivate }"
                                                class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                :class="{ 'font-medium text-green-600': !isPrivate, 'text-gray-400': isPrivate }"
                                                class="text-sm transition-colors duration-200">
                                                Public
                                            </span>
                                        </div>

                                        <button @click.prevent="isPrivate = !isPrivate"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="{ 'bg-green-200': !isPrivate, 'bg-red-200': isPrivate }">
                                            <span class="sr-only">Toggle lobby visibility</span>
                                            <span aria-hidden="true"
                                                class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="{ 'translate-x-5': isPrivate, 'translate-x-0': !isPrivate }">
                                            </span>
                                        </button>

                                        <div class="flex items-center gap-2">
                                            <svg :class="{ 'text-red-600': isPrivate, 'text-gray-400': !isPrivate }"
                                                class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span
                                                :class="{ 'font-medium text-red-600': isPrivate, 'text-gray-400': !isPrivate }"
                                                class="text-sm transition-colors duration-200">
                                                Private
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">End time:</label>
                                <div>
                                    <input type="datetime-local" name="ending_time" required
                                        class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                        Create Wager
                    </button>
                    </form>
                    <button type="button" @click = " showModal = false "
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
