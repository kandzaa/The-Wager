<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Friends activity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('Friends.user-search')

                    <div class="max-w-2xl mx-auto">
                        {{-- Fixed: Check if friends collection is empty --}}
                        @if (Auth::user()->friends == null || Auth::user()->friends->isEmpty())
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-gray-600 text-lg">You have no friends added yet.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($friends as $friend)
                                    <div class="p-4 border rounded-lg hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <span class="text-xl font-semibold text-gray-600">
                                                        {{ substr($friend->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-semibold">{{ $friend->name }}</h3>
                                                    <p class="text-gray-600">Joined
                                                        {{ $friend->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            {{-- Fixed: Simplified route parameter --}}
                                            <a href="{{ route('Friends.user.show', $friend->id) }}"
                                                class="px-4 py-2 text-green-600 hover:text-green-700 transition-colors border border-green-600 rounded hover:bg-green-50">
                                                View Profile
                                            </a>
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
</x-app-layout>
