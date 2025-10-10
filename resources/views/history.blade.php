<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                        Wager History
                    </h1>
                    <p class="mt-3 text-lg text-slate-600 dark:text-slate-300">
                        Review your past wagers and their outcomes
                    </p>
                </div>

            </div>

            <div x-data="{ activeTab: 'yourWagers' }">
                <!-- Your Wagers Tab -->
                <div x-show="activeTab === 'yourWagers'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if ($userWagers->isEmpty())
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 text-center">
                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                                <ion-icon name="time-outline" class="h-6 w-6 text-slate-400"></ion-icon>
                            </div>
                            <h3 class="mt-3 text-lg font-medium text-slate-900 dark:text-white">No wager history yet
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Your ended wagers will appear here once you participate in some.
                            </p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($userWagers as $wager)
                                @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $userWagers->links() }}
                        </div>
                    @endif
                </div>

                <!-- Public Wagers Tab -->
                <div x-show="activeTab === 'publicWagers'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if ($publicWagers->isEmpty())
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 text-center">
                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                                <ion-icon name="globe-outline" class="h-6 w-6 text-slate-400"></ion-icon>
                            </div>
                            <h3 class="mt-3 text-lg font-medium text-slate-900 dark:text-white">No public wagers found
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                There are no public ended wagers at the moment.
                            </p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($publicWagers as $wager)
                                @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $publicWagers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize Alpine.js data
            document.addEventListener('alpine:init', () => {
                Alpine.data('historyTabs', () => ({
                    activeTab: 'yourWagers',
                    init() {
                        // Check URL hash for tab
                        const hash = window.location.hash.substring(1);
                        if (hash === 'public') {
                            this.activeTab = 'publicWagers';
                        }
                    },
                    updateUrl() {
                        const hash = this.activeTab === 'publicWagers' ? '#public' : '';
                        history.pushState(null, null, window.location.pathname + hash);
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
