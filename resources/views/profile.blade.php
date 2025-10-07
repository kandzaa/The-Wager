<x-app-layout>


    <div
        class="select-none min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-8">
                    <div
                        class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    {{ Auth::user()->name }} Profile
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Change username
                                </h3>
                                <form action="{{ route('profile.change-username') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                                        class="mb-4 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white" />
                                    <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-500 transition">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 mb-8 border border-slate-300/60 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Change email
                                </h3>
                                <form action="{{ route('profile.change-email') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="text" name="email" value="{{ Auth::user()->email }}"
                                        class="mb-4 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white" />
                                    <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-500 transition">Update</button>
                                </form>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
