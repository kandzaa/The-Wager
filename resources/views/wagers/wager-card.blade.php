<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    <div class="container mx-auto px-4 py-10">
        <div class="mb-12">

            <h2 class="text-2xl font-semibold tracking-tight text-slate-100 mb-6">Available Wagers</h2>

            <div x-data="{ showModal: false }" x-effect="document.body.classList.toggle('overflow-hidden', showModal)">
                <button @click="showModal = true"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-500 focus:outline-none transition-colors duration-150 shadow-sm">
                    Create Wager
                </button>
                <div x-show="showModal">
                    @include('wagers.create_wager', ['friends' => $friends])
                </div>
            </div>

            @include('wagers.wager-search')

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($wagers as $wager)
                    @if ($wager->status == 'public')
                        @include('wagers.wager-item', ['wager' => $wager])
                    @endif
                @endforeach
            </div>

        </div>

        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-slate-100 mb-6">Your Wagers</h2>
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
