<x-app-layout>
    <x-slot name="header">
        <div
            class="select-none bg-gradient-to-r from-slate-200 to-slate-300 dark:from-gray-600 dark:to-gray-700 rounded-xl p-6 shadow-lg">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                {{ 'Hello, ' . Auth::user()->name }}!
            </h2>
        </div>
    </x-slot>

    <div
        class="select-none min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700">
                            <div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Total Users
                                    </h3>
                                    <div
                                        class="flex items-center gap-2 text-2xl font-bold text-slate-900 dark:text-slate-100">
                                        <ion-icon name="people-circle"></ion-icon>
                                        {{ $usersCount ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-slate-100/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>

                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Total
                                        Wagers
                                    </h3>
                                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                                        {{ $wagersCount ?? 0 }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pendingInvitations->isNotEmpty())
                    <div class="bg-white/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700 mt-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Pending Wager Invitations</h3>
                        <div class="space-y-4">
                            @foreach($pendingInvitations as $invitation)
                                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/60 rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                                    <div>
                                        <h4 class="font-medium text-slate-800 dark:text-slate-100">{{ $invitation->wager->name }}</h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            Invited by: {{ $invitation->wager->creator->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                            Expires: {{ $invitation->expires_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('invitations.accept', $invitation->token) }}" 
                                           class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Accept
                                        </a>
                                        <a href="{{ route('invitations.decline', $invitation->token) }}" 
                                           class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Decline
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($joinedWagers->isNotEmpty())
                    <div class="bg-white/80 dark:bg-slate-800/40 backdrop-blur-sm rounded-xl p-6 border border-slate-300/60 dark:border-slate-700 mt-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Your Active Wagers</h3>
                        <div class="space-y-4">
                            @foreach($joinedWagers as $player)
                                @php $wager = $player->wager; @endphp
                                <a href="{{ route('wagers.show', $wager) }}" class="block group">
                                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/60 rounded-lg border border-slate-200/60 dark:border-slate-700/60 group-hover:border-blue-500 dark:group-hover:border-blue-500 transition-colors duration-200">
                                        <div>
                                            <h4 class="font-medium text-slate-800 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                                {{ $wager->name }}
                                            </h4>
                                            <div class="flex items-center gap-4 mt-1">
                                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                                    <span class="font-medium">Creator:</span> {{ $wager->creator->name }}
                                                </p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                                    <span class="font-medium">Players:</span> {{ $wager->players_count }}/{{ $wager->max_players }}
                                                </p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                                    <span class="font-medium">Ends:</span> {{ $wager->ending_time->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-blue-600 dark:text-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
</x-app-layout>
