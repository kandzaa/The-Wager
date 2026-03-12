<x-app-layout>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="mb-6">
                            <a href="{{ route('admin') }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                ← Back to Admin
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">User Information</h2>
                                <div class="space-y-3">
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">ID:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->id }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">Name:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">Email:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->email }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">Role:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->role }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">Balance:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->balance }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">Joined:</span>
                                        <span class="text-slate-700 dark:text-slate-200 ml-2">{{ $user->created_at->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Actions</h2>
                                <div class="space-y-3">
                                    <a href="{{ route('admin.Manage.users.edit', $user->id) }}" 
                                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                        Edit User
                                    </a>
                                    <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                            Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
