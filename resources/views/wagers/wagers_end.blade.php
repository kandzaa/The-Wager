<div id="endWagerModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
    onclick="if(event.target === this) closeEndWagerModal()">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm"></div>

    {{-- Modal --}}
    <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-[#0f1419] border border-slate-200 dark:border-white/[0.08] shadow-2xl max-h-[85vh] overflow-y-auto"
         onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-white/[0.06]">
            <div>
                <p class="text-xs uppercase tracking-[0.15em] text-emerald-600 dark:text-emerald-500 font-bold mb-0.5">Creator Action</p>
                <h2 class="text-lg font-black tracking-tight text-slate-900 dark:text-white">End Wager</h2>
            </div>
            <button onclick="closeEndWagerModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.06] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="px-6 py-6">
            @if($wager->choices && count($wager->choices) > 0)
                <form method="POST" action="{{ route('wagers.end', $wager) }}" id="endWagerForm">
                    @csrf
                    <input type="hidden" name="winning_choice_id" id="winning_choice_id_input" value="">

                    {{-- Step 1: Select choice --}}
                    <div id="selection-step">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Select the winning choice:</p>
                        <div class="space-y-2 mb-6">
                            @foreach($wager->choices as $choice)
                            @php
                                $choiceId = is_object($choice) ? $choice->id : $choice['id'];
                                $choiceLabel = is_object($choice) ? $choice->label : $choice['label'];
                                $totalBet = is_object($choice) ? ($choice->total_bet ?? 0) : ($choice['total_bet'] ?? 0);
                            @endphp
                            <button type="button"
                                onclick="selectWinningChoice({{ $choiceId }}, '{{ addslashes($choiceLabel) }}')"
                                class="choice-btn w-full text-left p-4 rounded-xl border-2
                                       bg-slate-50 dark:bg-white/[0.02]
                                       border-slate-200 dark:border-white/[0.07]
                                       hover:border-emerald-500 dark:hover:border-emerald-500/60
                                       hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                       text-slate-900 dark:text-white
                                       transition-all duration-200"
                                data-choice-id="{{ $choiceId }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-sm">{{ $choiceLabel }}</span>
                                    <span class="text-xs text-slate-400 dark:text-slate-600">{{ number_format($totalBet, 0) }} bet</span>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step 2: Confirm --}}
                    <div id="confirmation-step" class="hidden">
                        <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-500/20 p-4 mb-5">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300 mb-1">Confirm Selection</p>
                                    <p class="text-sm text-amber-700 dark:text-amber-400">
                                        Winner: <strong id="selected-choice-name" class="font-black"></strong>
                                    </p>
                                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-1">This cannot be undone. All bets will be settled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" id="confirm-end-btn"
                                class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                                <span id="confirm-btn-text">Confirm & End Wager</span>
                            </button>
                            <button type="button" onclick="resetEndWagerModal()"
                                class="px-5 py-3 bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] text-slate-700 dark:text-slate-400 font-bold rounded-xl transition-all duration-200 text-sm">
                                Back
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-500/20 p-4">
                    <p class="text-sm text-amber-800 dark:text-amber-300">No choices available. Add choices before ending.</p>
                    <button onclick="closeEndWagerModal()" class="mt-3 text-xs text-amber-600 dark:text-amber-400 font-semibold hover:underline">← Close</button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
#endWagerModal:not(.hidden) { display: flex !important; animation: modalIn 0.2s ease-out; }
@keyframes modalIn { from{opacity:0;transform:scale(0.97)} to{opacity:1;transform:scale(1)} }
.choice-btn:hover { transform: translateY(-1px); }
</style>

<script>
let selectedChoiceId = null;

window.openEndWagerModal = function() {
    document.getElementById('endWagerModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    resetEndWagerModal();
}
window.closeEndWagerModal = function() {
    document.getElementById('endWagerModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
window.selectWinningChoice = function(id, label) {
    selectedChoiceId = id;
    document.getElementById('winning_choice_id_input').value = id;
    document.getElementById('selected-choice-name').textContent = label;
    document.getElementById('selection-step').classList.add('hidden');
    document.getElementById('confirmation-step').classList.remove('hidden');
}
window.resetEndWagerModal = function() {
    selectedChoiceId = null;
    document.getElementById('winning_choice_id_input').value = '';
    document.getElementById('selection-step').classList.remove('hidden');
    document.getElementById('confirmation-step').classList.add('hidden');
    const btn = document.getElementById('confirm-end-btn');
    if (btn) { btn.disabled = false; document.getElementById('confirm-btn-text').textContent = 'Confirm & End Wager'; }
    document.querySelectorAll('.bg-red-50, .dark\\:bg-red-900\\/20').forEach(el => el.tagName !== 'BUTTON' && el.remove());
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('endWagerForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('winning_choice_id_input').value;
    if (!id) { alert('Please select a winning choice'); return; }
    const btn = document.getElementById('confirm-end-btn');
    const txt = document.getElementById('confirm-btn-text');
    btn.disabled = true;
    txt.textContent = 'Processing...';

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ winning_choice_id: parseInt(id) }),
            credentials: 'same-origin'
        });

        const data = await res.json();

        if (data.success && data.redirect) {
            txt.textContent = 'Success! Redirecting...';
            window.location.href = data.redirect;
            return;
        }

        // Even on error, if wager was ended redirect to results
        if (!data.success && data.redirect) {
            window.location.href = data.redirect;
            return;
        }

        throw new Error(data.message || 'Failed');

    } catch (err) {
        // If we got a network/parse error, check if wager ended by reloading
        txt.textContent = 'Checking status...';
        setTimeout(() => { window.location.reload(); }, 1500);
    }
});
    }
    document.getElementById('endWagerButton')?.addEventListener('click', e => { e.preventDefault(); openEndWagerModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEndWagerModal(); });
});
</script>