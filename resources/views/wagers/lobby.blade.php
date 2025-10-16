<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
        <div x-data="{ showModal: false }" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12"
            x-effect="document.body.classList.toggle('overflow-hidden', showModal)">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Wagers Lobby</h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Create and join exciting wagers with other
                        players</p>
                </div>
                <div>
                    <button @click="showModal = true"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200">
                        <ion-icon class="size-5" name="add-circle-outline"></ion-icon>
                        <span>Create Wager</span>
                    </button>
                    <div x-show="showModal" @close-create-wager-modal.window="showModal = false">
                        @include('wagers.create_wager')
                    </div>
                </div>
            </div>

        </div>

        @include('wagers.wager-search')

        <!-- Wager Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 mb-5">
                    Available Wagers
                </h2>



                @forelse ($wagers as $wager)
                    @if ($wager)
                        <div class="wager-item-container">
                            @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                        </div>
                    @endif
                @empty
                    <div class="no-wagers-message ...">
                        <p>No public wagers available</p>
                        <p>Be the first to create one!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Your Wagers Section -->
        <div class="lg:col-span-4">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 mb-5">
                Your Wagers
            </h2>

            @if ($userWagers->isEmpty())
                <div
                    class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-6 text-center">
                    <p class="text-sm text-slate-600 dark:text-slate-300">You haven't created any wagers yet.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($userWagers as $wager)
                        @include('wagers.wager-item', ['wager' => $wager, 'compact' => true])
                    @endforeach
                </div>
            @endif
        </div>

    </div>
    </div>
    </div>
</x-app-layout>
