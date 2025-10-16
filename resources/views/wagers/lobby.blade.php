<x-app-layout>
    <div x-data="{ showModal: false }"
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12"
            x-effect="document.body.classList.toggle('overflow-hidden', showModal)">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                        Wagers Lobby
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Create and join exciting wagers with other players
                    </p>
                </div>
                <button @click="showModal = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200">
                    <ion-icon class="size-5" name="add-circle-outline"></ion-icon>
                    <span>Create Wager</span>
                </button>
            </div>

            <!-- Create Wager Modal -->
            <div x-show="showModal" @close-create-wager-modal.window="showModal = false">
                @include('wagers.create_wager')
            </div>

            <!-- Search Bar -->
            <div class="flex justify-center mb-8">
                <div class="relative w-full max-w-md">
                    <input type="text" id="wager-search-input" placeholder="Search wagers by name..."
                        autocomplete="off"
                        class="w-full p-3 pl-10 bg-white dark:bg-slate-900/40 backdrop-blur border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-slate-400 dark:text-slate-400 absolute left-3 top-3.5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Wager Sections -->
            <div class="lg:col-span-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 mb-5">
                    Available Wagers
                </h2>
                @if (empty($wagers) || $wagers->isEmpty())
                    <div
                        class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-12 text-center">
                        <p class="text-base text-slate-600 dark:text-slate-300 mb-2">No public wagers available</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Be the first to create one!</p>
                    </div>
                @else
                    @php
                        $publicWagers = $wagers->filter(fn($w) => ($w->status ?? null) === 'public');
                    @endphp
                    <div class="wagers-list space-y-4">
                        @if ($publicWagers->isEmpty())
                            <div
                                class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-12 text-center">
                                <p class="text-base text-slate-600 dark:text-slate-300 mb-2">No public wagers available
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Be the first to create one!</p>
                            </div>
                        @else
                            @foreach ($publicWagers as $wager)
                                @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>

            <!-- Your Wagers -->
            <!-- Replace the Available Wagers section with this -->
            <div class="lg:col-span-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 mb-5">
                    Available Wagers
                </h2>

                @php
                    $publicWagers = collect($wagers)->filter(function ($w) {
                        return isset($w->status) && $w->status === 'public';
                    });
                @endphp

                @if ($publicWagers->isEmpty())
                    <div
                        class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-12 text-center">
                        <p class="text-base text-slate-600 dark:text-slate-300 mb-2">No public wagers available</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Be the first to create one!</p>
                    </div>
                @else
                    <div class="wagers-list space-y-4">
                        @foreach ($publicWagers as $wager)
                            @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Replace Your Wagers section with this -->
            <div class="lg:col-span-4">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100 mb-5">
                    Your Wagers
                </h2>

                @auth
                    @php
                        $yourWagers = collect($wagers)->filter(function ($w) {
                            return isset($w->creator_id) && $w->creator_id == auth()->id();
                        });
                    @endphp

                    @if ($yourWagers->isEmpty())
                        <div
                            class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-6 text-center">
                            <p class="text-sm text-slate-600 dark:text-slate-300">You haven't created any wagers yet.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($yourWagers as $wager)
                                @include('wagers.wager-item', ['wager' => $wager, 'compact' => true])
                            @endforeach
                        </div>
                    @endif
                @else
                    <div
                        class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-6 text-center">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Please log in to see your wagers.</p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
