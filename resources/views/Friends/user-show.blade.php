{{-- resources/views/user/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-white shadow rounded-lg p-6">
                            <div class="flex items-center space-x-6 mb-6">
                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-3xl font-semibold text-gray-600">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                    <p class="text-gray-600">Member since {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $user->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $user->email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Joined</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">
                                        {{ $user->created_at->diffForHumans() }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">
                                        {{ $user->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <a href="{{ route('friends') }}"
                                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                                    Back to Friends
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
