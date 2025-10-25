<div id="endWagerModal"
    class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden"
    onclick="if(event.target === this) closeEndWagerModal()">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto"
        onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                End Wager: {{ $wager->name }}
            </h1>
            <button type="button" onclick="closeEndWagerModal()"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if ($wager->choices && count($wager->choices) > 0)
            <div id="endWagerContent">
                <form method="POST" action="{{ route('wagers.end', $wager) }}" id="endWagerForm">
                    @csrf
                    <input type="hidden" name="winning_choice_id" id="winning_choice_id_input" value="">

                    <!-- Selection Step -->
                    <div id="selection-step">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                            Select the winning choice to end this wager:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                            @foreach ($wager->choices as $choice)
                                @php
                                    $choiceId = is_object($choice) ? $choice->id : $choice['id'];
                                    $choiceLabel = is_object($choice) ? $choice->label : $choice['label'];
                                    $totalBet = is_object($choice)
                                        ? $choice->total_bet ?? 0
                                        : $choice['total_bet'] ?? 0;
                                @endphp
                                <button type="button"
                                    onclick="selectWinningChoice({{ $choiceId }}, '{{ addslashes($choiceLabel) }}')"
                                    class="choice-btn w-full text-left p-4 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:border-emerald-500 dark:hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 shadow-sm hover:shadow-md transition-all duration-200"
                                    data-choice-id="{{ $choiceId }}" data-choice-label="{{ $choiceLabel }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">{{ $choiceLabel }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ number_format($totalBet, 0) }} bet
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Confirmation Step -->
                    <div id="confirmation-step" class="hidden">
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-1">
                                        Confirm Your Selection
                                    </h4>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-2">
                                        You've selected: <strong id="selected-choice-name"
                                            class="font-semibold"></strong>
                                    </p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        This action cannot be undone. All bets will be settled and winners will receive
                                        their payouts.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" id="confirm-end-btn"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span id="confirm-btn-text">Confirm and End Wager</span>
                            </button>
                            <button type="button" onclick="resetEndWagerModal()"
                                class="px-6 py-3 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg font-medium transition-all duration-200">
                                Back
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div
                class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-yellow-800 dark:text-yellow-200">
                    No choices available for this wager. Please add choices before ending the wager.
                </p>
                <button type="button" onclick="closeEndWagerModal()"
                    class="mt-3 text-sm text-yellow-700 dark:text-yellow-300 hover:underline">
                    ← Close
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    // Global variables for end wager modal
    let selectedChoiceId = null;
    let selectedChoiceLabel = null;

    // Open the end wager modal
    window.openEndWagerModal = function() {
        const modal = document.getElementById('endWagerModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            resetEndWagerModal();
        }
    }

    // Close the end wager modal
    window.closeEndWagerModal = function() {
        const modal = document.getElementById('endWagerModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetEndWagerModal();
        }
    }

    // Select a winning choice
    window.selectWinningChoice = function(choiceId, choiceLabel) {
        selectedChoiceId = choiceId;
        selectedChoiceLabel = choiceLabel;

        // Update hidden input
        const hiddenInput = document.getElementById('winning_choice_id_input');
        if (hiddenInput) {
            hiddenInput.value = choiceId;
        }

        // Update confirmation text
        const selectedName = document.getElementById('selected-choice-name');
        if (selectedName) {
            selectedName.textContent = choiceLabel;
        }

        // Show confirmation step
        const selectionStep = document.getElementById('selection-step');
        const confirmationStep = document.getElementById('confirmation-step');

        if (selectionStep) selectionStep.classList.add('hidden');
        if (confirmationStep) confirmationStep.classList.remove('hidden');
    }

    // Reset the modal to initial state
    window.resetEndWagerModal = function() {
        selectedChoiceId = null;
        selectedChoiceLabel = null;

        const hiddenInput = document.getElementById('winning_choice_id_input');
        if (hiddenInput) {
            hiddenInput.value = '';
        }

        const selectionStep = document.getElementById('selection-step');
        const confirmationStep = document.getElementById('confirmation-step');
        const confirmBtn = document.getElementById('confirm-end-btn');
        const confirmBtnText = document.getElementById('confirm-btn-text');

        if (selectionStep) selectionStep.classList.remove('hidden');
        if (confirmationStep) confirmationStep.classList.add('hidden');
        if (confirmBtn) confirmBtn.disabled = false;
        if (confirmBtnText) confirmBtnText.textContent = 'Confirm and End Wager';

        // Remove any error messages
        const errorDivs = document.querySelectorAll('.bg-red-50');
        errorDivs.forEach(div => div.remove());
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('endWagerForm');

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const winningChoiceId = document.getElementById('winning_choice_id_input').value;

                if (!winningChoiceId) {
                    alert('Please select a winning choice');
                    return;
                }

                const confirmBtn = document.getElementById('confirm-end-btn');
                const confirmBtnText = document.getElementById('confirm-btn-text');
                const originalBtnText = confirmBtnText.textContent;

                confirmBtn.disabled = true;
                confirmBtnText.textContent = 'Processing...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            winning_choice_id: parseInt(winningChoiceId)
                        }),
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to end wager');
                    }

                    if (data.success) {
                        // Show success message
                        confirmBtnText.textContent = 'Success! Redirecting...';

                        // Redirect after a short delay
                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 500);
                    } else {
                        throw new Error(data.message || 'Failed to end wager');
                    }
                } catch (error) {
                    console.error('Error ending wager:', error);

                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className =
                        'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-lg p-4 mb-4';
                    errorDiv.innerHTML = `
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Error ending wager</h4>
                            <p class="text-sm text-red-700 dark:text-red-300">${error.message}</p>
                        </div>
                    </div>
                `;

                    // Remove old errors
                    const oldErrors = form.querySelectorAll('.bg-red-50');
                    oldErrors.forEach(el => el.remove());

                    // Insert error at the top of the form
                    form.insertBefore(errorDiv, form.firstChild);
                    errorDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    confirmBtn.disabled = false;
                    confirmBtnText.textContent = originalBtnText;
                }
            });
        }

        // End Wager Button Click Handler
        const endWagerButton = document.getElementById('endWagerButton');
        if (endWagerButton) {
            endWagerButton.addEventListener('click', function(e) {
                e.preventDefault();
                openEndWagerModal();
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEndWagerModal();
            }
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #endWagerModal:not(.hidden)>div {
        animation: fadeIn 0.2s ease-out;
    }

    .choice-btn:hover {
        transform: translateY(-2px);
    }

    .choice-btn:active {
        transform: translateY(0);
    }
</style>
