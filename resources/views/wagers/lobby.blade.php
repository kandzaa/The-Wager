<x-app-layout>
<div x-data="{ showModal: false }"
     class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden"
     x-effect="document.body.classList.toggle('overflow-hidden', showModal)">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute -top-20 right-1/3 w-[700px] h-[500px] bg-emerald-900/15 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[500px] h-[500px] bg-emerald-950/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-14">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-10 fade-up">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-2">Arena</p>
                <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Wagers Lobby</h1>
                <p class="text-sm text-slate-500 mt-1">Create and join wagers with other players</p>
            </div>
            <button @click="showModal = true"
                class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-emerald-900/30 active:scale-95 shrink-0 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Create Wager
            </button>
        </div>

        {{-- Search --}}
        @include('wagers.wager-search')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Available Wagers --}}
            <div class="lg:col-span-2 fade-up" style="animation-delay:80ms">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                    <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Available Wagers</h2>
                </div>
                <div class="space-y-3" id="wagers-list">
                    @forelse($wagers as $wager)
                        @if($wager)
                            <div class="wager-item-container">
                                @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
                            </div>
                        @endif
                    @empty
                        <div class="no-wagers-message rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] p-12 text-center shadow-sm dark:shadow-none">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 dark:text-slate-400 font-semibold mb-1">No wagers yet</p>
                            <p class="text-slate-500 text-sm">Be the first to create one!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Your Wagers --}}
            <div class="fade-up" style="animation-delay:160ms">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-5 bg-slate-400 dark:bg-slate-600 rounded-full"></div>
                    <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Your Wagers</h2>
                </div>
                @if($userWagers->isEmpty())
                    <div class="rounded-2xl bg-white dark:bg-white/[0.02] border border-dashed border-slate-300 dark:border-white/[0.06] p-8 text-center shadow-sm dark:shadow-none">
                        <p class="text-slate-500 text-sm">You haven't created any wagers yet.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($userWagers as $wager)
                            @include('wagers.wager-item', ['wager' => $wager, 'compact' => true])
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Create Modal --}}
    <div x-show="showModal" @close-create-wager-modal.window="showModal = false">
        @include('wagers.create_wager')
    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>