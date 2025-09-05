<div class="container mx-auto px-4 py-8">
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Available Wagers</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($wagers as $wager)
                @if ($wager->status != 'private')
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 overflow-hidden">

                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                            <h3 class="font-bold text-xl text-white mb-1">{{ $wager->name }}</h3>
                            <div class="flex justify-between items-center">
                                <span
                                    class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-semibold text-white">
                                    {{ ucfirst($wager->status) }}
                                </span>
                                <span class="text-white/90 text-sm">
                                    {{ count(json_decode($wager->players, true)) }}/{{ $wager->max_players }} players
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $wager->description }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                    <p class="text-green-600 font-semibold text-lg">
                                        {{ number_format($wager->entry_fee, 2) }}
                                    </p>
                                    <p class="text-green-500 text-xs uppercase tracking-wide">Entry Fee</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                    <p class="text-yellow-600 font-semibold text-lg">
                                        {{ number_format($wager->pot, 2) }}
                                    </p>
                                    <p class="text-yellow-500 text-xs uppercase tracking-wide">Total Pot</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Players</span>
                                    <span>{{ count(json_decode($wager->players, true)) }}/{{ $wager->max_players }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ (count(json_decode($wager->players, true)) / $wager->max_players) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-4 border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                                <span>End: {{ \Carbon\Carbon::parse($wager->ending_time)->diffForHumans() }}</span>
                            </div>
                            <button
                                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                                Join Wager
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Your Wagers</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($wagers as $wager)
                @if ($wager->creator->name == Auth::user()->name)
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-green-200 overflow-hidden">

                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="font-bold text-xl text-white">{{ $wager->name }}</h3>
                                <span
                                    class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                    OWNER
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span
                                    class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-semibold text-white">
                                    {{ ucfirst($wager->status) }}
                                </span>
                                <span class="text-white/90 text-sm">
                                    {{ count(json_decode($wager->players, true)) }}/{{ $wager->max_players }} players
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $wager->description }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                    <p class="text-green-600 font-semibold text-lg">
                                        {{ number_format($wager->entry_fee, 2) }}
                                    </p>
                                    <p class="text-green-500 text-xs uppercase tracking-wide">Entry Fee</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                    <p class="text-yellow-600 font-semibold text-lg">
                                        {{ number_format($wager->pot, 2) }}
                                    </p>
                                    <p class="text-yellow-500 text-xs uppercase tracking-wide">Total Pot</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Players</span>
                                    <span>{{ count(json_decode($wager->players, true)) }}/{{ $wager->max_players }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ (count(json_decode($wager->players, true)) / $wager->max_players) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-4 border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-3">

                                <span>End: {{ \Carbon\Carbon::parse($wager->ending_time)->diffForHumans() }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 text-sm">
                                    Edit
                                </button>
                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 text-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
