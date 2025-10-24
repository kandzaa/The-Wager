<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">End Wager: {{ $wager->name }}</h1>

            @if ($wager->choices && count($wager->choices) > 0)
                <div x-data="{
                    selectedChoiceId: null,
                    confirming: false,
                    choices: {{ json_encode($wager->choices) }}
                }" class="space-y-6">
                    <form method="POST" action="{{ route('wagers.end', $wager) }}" id="endWagerForm">
                        @csrf
                        <input type="hidden" name="winning_choice_id" x-model="selectedChoiceId">

                        <div x-show="!confirming">
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Select the winning choice:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($wager->choices as $choice)
                                    @php
                                        $choiceId = is_object($choice) ? $choice->id : $choice['id'];
                                        $choiceLabel = is_object($choice) ? $choice->label : $choice['label'];
                                    @endphp
                                    <button type="button"
                                        @click="selectedChoiceId = {{ $choiceId }}; confirming = true;"
                                        class="w-full text-left p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700/80 shadow-sm transition"
                                        data-choice-id="{{ $choiceId }}" data-choice-label="{{ $choiceLabel }}">
                                        <span class="font-medium">{{ $choiceLabel }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="confirming" x-cloak>
                            <p class="text-sm text-slate-700 dark:text-slate-200 mb-4">
                                You've selected:
                                <span class="font-medium"
                                    x-text="selectedChoiceId ? (document.querySelector(`button[data-choice-id='${selectedChoiceId}']`)?.getAttribute('data-choice-label') || 'Unknown') : ''"></span>
                            </p>

                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                                Are you sure you want to end this wager and select this as the winning choice? This
                                action cannot be undone.
                            </p>

                            <div class="flex items-center space-x-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    x-bind:disabled="!selectedChoiceId">
                                    Confirm and End Wager
                                </button>
                                <button type="button" @click="confirming = false; selectedChoiceId = null;"
                                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg font-medium transition">
                                    Cancel
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
                    <a href="{{ route('wagers.show', $wager) }}"
                        class="mt-2 inline-block text-sm text-yellow-700 dark:text-yellow-300 hover:underline">
                        ← Back to Wager
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('endWagerForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const winningChoiceId = formData.get('winning_choice_id');

                        if (!winningChoiceId) {
                            alert('Please select a winning choice');
                            return;
                        }

                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;

                        submitBtn.disabled = true;
                        submitBtn.innerHTML = 'Ending...';

                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    winning_choice_id: winningChoiceId
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => Promise.reject(err));
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Show success message
                                    alert(data.message || 'Wager ended successfully!');

                                    // Redirect
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else {
                                        window.location.href = "{{ route('wagers.show', $wager) }}";
                                    }
                                } else {
                                    throw new Error(data.message || 'Failed to end wager');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert(error.message || 'An error occurred while ending the wager');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
