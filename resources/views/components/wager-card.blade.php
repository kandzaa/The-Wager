<div>
    @foreach ($wagers as $wager)
        <div class="max-w-sm rounded overflow-hidden shadow-lg m-4 bg-white">
            <div class="px-6 py-4">
                <div class="font-bold text-xl mb-2">{{ $wager->name }}</div>
                <p class="text-gray-700 text-base mb-2">
                    {{ $wager->description }}
                </p>
                <p class="text-gray-700 text-base">
                    Entry Fee: {{ number_format($wager->entry_fee, 2) }}
                </p>
                <p class="text-gray-700 text-base">
                    Pot: {{ number_format($wager->pot, 2) }}
                </p>
                <p class="text-gray-700 text-base">
                    Players: {{ count(json_decode($wager->players, true)) }}/{{ $wager->max_players }}
                </p>
                <p class="text-gray-600 text-sm mt-2">
                    Created by: {{ $wager->creator->name }}
                </p>
                <p class="text-gray-600 text-sm">
                    Ends: {{ \Carbon\Carbon::parse($wager->ending_time)->diffForHumans() }}
                </p>
            </div>
            <div class="px-6 pt-4 pb-2">
                <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                    {{ ucfirst($wager->status) }}
                </span>
            </div>
        </div>
    @endforeach
</div>
