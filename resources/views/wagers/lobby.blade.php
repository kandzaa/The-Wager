<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
        <div class="container mx-auto px-4 py-10" x-data="{ showModal: false }"
            x-effect="document.body.classList.toggle('overflow-hidden', showModal)">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">Wagers Lobby</h1>
                <button @click="showModal = true"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-500 focus:outline-none transition-colors duration-150 shadow-sm">
                    Create Wager
                </button>
            </div>

            <div x-show="showModal">
                @include('wagers.create_wager', ['friends' => $friends ?? collect()])
            </div>

            @include('wagers.wager-search')

            @php
                $allWagers = $wagers ?? collect();
                $publicWagers = $allWagers->filter(fn($w) => ($w->status ?? null) === 'public');
                $yourWagers = $allWagers->filter(
                    fn($w) => isset($w->creator_id) && isset(Auth::user()->id) && $w->creator_id == Auth::user()->id,
                );
            @endphp

            <div class="mt-10">
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 mb-6">Available
                    Wagers</h2>
                @if ($publicWagers->isEmpty())
                    <div
                        class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-600 dark:text-slate-300">
                        <p class="text-base">No public wagers are available right now.</p>
                        <p class="text-sm mt-2">Be the first to create one!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($publicWagers as $wager)
                            @include('wagers.wager-item', ['wager' => $wager])
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-12">
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 mb-6">Your Wagers
                </h2>
                @if ($yourWagers->isEmpty())
                    <div
                        class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-600 dark:text-slate-300">
                        <p class="text-base">You haven't created any wagers yet.</p>
                        <p class="text-sm mt-2">Create one to get started.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($yourWagers as $wager)
                            @include('wagers.wager-item', ['wager' => $wager])
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
