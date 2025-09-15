<x-app-layout>
    <x-slot name="header">
        <div
            class="bg-gradient-to-r from-slate-200 to-slate-300 dark:from-gray-600 dark:to-gray-700 rounded-xl p-6 shadow-lg">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                {{ __('Friends') }}
            </h2>
        </div>
    </x-slot>

    <div
        class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-300/60 dark:border-slate-800 overflow-hidden">
                    <div class="p-8">
                        @include('Friends.user-search')

                        @if(isset($incomingRequests) && $incomingRequests->isNotEmpty())
                            <div class="max-w-4xl mx-auto mb-10">
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Pending Friend Requests
                                    </h3>
                                </div>
                                <div class="space-y-3">
                                    @foreach($incomingRequests as $req)
                                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-300/60 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/40">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">
                                                    {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-slate-800 dark:text-slate-100 font-semibold">{{ $req->requester->name }}</div>
                                                    <div class="text-slate-500 dark:text-slate-400 text-sm">wants to be your friend</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button onclick="acceptRequest({{ $req->id }})"
                                                    class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium transition">Accept</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="max-w-4xl mx-auto">
                            @if ($friends->isEmpty())
                                <div class="text-center py-16">
                                    <div class="relative inline-block">
                                        <div
                                            class="w-32 h-32 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-full flex items-center justify-center mb-6 mx-auto shadow-inner">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-16 w-16 text-emerald-400 dark:text-emerald-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-3">
                                        Your Network Awaits
                                    </h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                                        Use the search above to discover and add friends!
                                    </p>
                                </div>
                            @else
                                <div class="mb-8">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div
                                            class="flex items-center space-x-2 bg-emerald-100 dark:bg-emerald-900/30 px-4 py-2 rounded-full border border-emerald-300 dark:border-emerald-800">
                                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                            <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-200">
                                                {{ $friends->count() }}
                                                {{ $friends->count() === 1 ? 'Friend' : 'Friends' }}
                                            </span>
                                        </div>
                                        <h3
                                            class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 dark:from-emerald-400 dark:to-emerald-500 bg-clip-text text-transparent">
                                            Your Circle
                                        </h3>
                                    </div>
                                </div>

                                <div class="grid gap-4 md:gap-6">
                                    @foreach ($friends as $friend)
                                        <div class="group relative overflow-hidden bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-slate-300/60 dark:border-slate-800 hover:bg-white/80 dark:hover:bg-slate-900/60 hover:border-slate-400/60 dark:hover:border-slate-700"
                                            data-friend-id="{{ $friend->id }}">

                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-transparent via-emerald-50/0 to-emerald-100/0 dark:via-emerald-900/0 dark:to-emerald-800/0 group-hover:via-emerald-50/30 group-hover:to-emerald-100/30 dark:group-hover:via-emerald-900/20 dark:group-hover:to-emerald-800/20 transition-all duration-500">
                                            </div>

                                            <div class="relative p-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-5">
                                                        <div class="relative">
                                                            <div
                                                                class="w-16 h-16 bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 dark:from-emerald-500 dark:via-emerald-600 dark:to-emerald-700 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                                                <span class="text-2xl font-bold text-white">
                                                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 dark:bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full">
                                                            </div>
                                                        </div>

                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-1">
                                                                {{ $friend->name }}
                                                            </h3>
                                                            <p class="text-slate-600 dark:text-slate-300 mb-2">
                                                                {{ $friend->email }}
                                                            </p>
                                                            <div
                                                                class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                Joined {{ $friend->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center space-x-3">
                                                        <a href="{{ route('user.show', $friend->id) }}"
                                                            class="group/btn flex items-center px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:text-white border-2 border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-600 dark:hover:bg-slate-600 hover:border-slate-600 transition-all duration-200 font-medium">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            View Profile
                                                        </a>

                                                        <button onclick="removeFriend({{ $friend->id }})"
                                                            class="group/btn flex items-center px-5 py-2.5 text-rose-500 dark:text-rose-400 hover:text-white border-2 border-rose-300 dark:border-rose-700 rounded-lg hover:bg-rose-500 dark:hover:bg-rose-600 hover:border-rose-500 transition-all duration-200 font-medium">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"
                                                                    clip-rule="evenodd" />
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function acceptRequest(requestId) {
            fetch('/friends/accept', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ request_id: requestId })
            }).then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Failed to accept');
                location.reload();
            }).catch(err => {
                alert(err.message || 'Failed to accept request');
            });
        }

        function removeFriend(friendId) {
            if (confirm('Are you sure you want to remove this friend?')) {
                const friendCard = document.querySelector(`[data-friend-id="${friendId}"]`);

                friendCard.style.opacity = '0.5';
                friendCard.style.pointerEvents = 'none';

                fetch('/friends/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            friend_id: friendId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            friendCard.style.transform = 'translateX(-100%)';
                            friendCard.style.opacity = '0';
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        friendCard.style.opacity = '1';
                        friendCard.style.pointerEvents = 'auto';
                        alert('An error occurred while removing the friend.');
                    });
            }
        }
    </script>
</x-app-layout>
