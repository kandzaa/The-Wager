<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wager lobby') }}
        </h2>
    </x-slot>

    <div x-data="{ showModal: false }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button @click="showModal = true"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-500 focus:outline-none  transition-colors duration-150">
                        Create Wager
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showModal">
            <x-create_wager />
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-wager-card :wagers="$wagers" />
                </div>
            </div>
        </div>
</x-app-layout>
