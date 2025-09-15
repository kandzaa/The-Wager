<div class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
    <div class="container mx-auto px-4 py-10">
        <div class="mb-12">

            <h2 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 mb-6">Available Wagers</h2>

            <div x-data="{ showModal: false }" x-effect="document.body.classList.toggle('overflow-hidden', showModal)">
                <button @click="showModal = true"
                    class="inline-flex items-center px-4 py-2 mb-6 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-500 focus:outline-none transition-colors duration-150 shadow-sm">
                    Create Wager
                </button>
                <div x-show="showModal">
                    @include('wagers.create_wager', ['friends' => $friends])
                </div>
            </div>

            @include('wagers.wager-search')

            @php
                $userId = Auth::user()->id ?? null;
                $joinedWagers = $wagers->filter(function ($w) use ($userId) {
                    $players = collect($w->players ?? []);
                    return $players->contains(function ($p) use ($userId) {
                        return is_array($p) && ($p['user_id'] ?? null) === $userId;
                    });
                });

                $joinedNonOwned = $joinedWagers->filter(fn($w) => $w->creator_id !== $userId);

                $publicWagers = $wagers->filter(function ($w) use ($userId, $joinedWagers) {
                    return $w->status === 'public'
                        && ! $joinedWagers->contains('id', $w->id)
                        && $w->creator_id !== $userId; // avoid showing your own wagers here
                });
            @endphp

            @if ($publicWagers->isEmpty())
                <div class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-600 dark:text-slate-300">
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

        <div class="mt-4">
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 mb-6">Joined Wagers</h2>
            @if ($joinedNonOwned->isEmpty())
                <div class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-600 dark:text-slate-300">
                    <p class="text-base">You haven't joined any wagers yet.</p>
                    <p class="text-sm mt-2">Browse available wagers above to join one.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($joinedNonOwned as $wager)
                        @include('wagers.wager-item', ['wager' => $wager])
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 mb-6">Your Wagers</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($wagers as $wager)
                    @if ($wager->creator_id == Auth::user()->id)
                        @include('wagers.wager-item', ['wager' => $wager])
                    @endif
                @endforeach
            </div>

        </div>
    </div>
</div>
