<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">All Users</h2>
</div>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
        <thead class="bg-slate-100/70 dark:bg-slate-800/40">
            <tr>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    ID</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Name</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Email</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Joined</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Balance</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Role</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                    Function</th>

            </tr>
        </thead>
        <tbody class="bg-white/70 dark:bg-slate-900/40 divide-y divide-slate-200 dark:divide-slate-800">
            @forelse ($users as $user)
                <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                    <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                        {{ $user->id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">
                        {{ $user->name }}</td>

                    <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                        {{ $user->email }}</td>

                    <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                        {{ $user->created_at?->diffForHumans() }}</td>

                    <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                        {{ $user->balance }}</td>

                    <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                        {{ $user->role }}</td>

                    <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                        <form action="{{ route('admin.Manage.users.destroy', $user->id) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-400"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                <ion-icon name="trash" class="text-xl"></ion-icon>
                            </button>
                        </form>
                        <span class="mx-1">|</span>
                        <a href="{{ route('admin.Manage.users.edit', $user->id) }}"
                            class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400"><ion-icon name="create"
                                class="text-xl"></ion-icon></a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No
                        users found.</td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>
