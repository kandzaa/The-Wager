<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">End Wager: {{ $wager->name }}</h1>
            
            <div x-data="{ selectedChoiceId: null, confirming: false }" class="space-y-6">
                <form method="POST" action="{{ route('wagers.end', $wager) }}" id="endWagerForm">
                    @csrf
                    <input type="hidden" name="winning_choice_id" x-model="selectedChoiceId">

                    <div x-show="!confirming">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Select the winning choice:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($wager->choices as $choice)
                                <button 
                                    type="button"
                                    @click="selectedChoiceId = {{ $choice->id }}; confirming = true;"
                                    class="w-full text-left p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700/80 shadow-sm transition"
                                    data-choice-id="{{ $choice->id }}"
                                >
                                    <span class="font-medium">{{ $choice->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="confirming" x-cloak>
                        <p class="text-sm text-slate-700 dark:text-slate-200 mb-4">
                            You've selected: 
                            <span class="font-medium" x-text="document.querySelector(`button[data-choice-id='${selectedChoiceId}']`).textContent.trim()"></span>
                        </p>
                        
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                            Are you sure you want to end this wager and select this as the winning choice? This action cannot be undone.
                        </p>
                        
                        <div class="flex items-center space-x-3">
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition"
                                x-bind:disabled="!selectedChoiceId"
                            >
                                Confirm and End Wager
                            </button>
                            <button 
                                type="button" 
                                @click="confirming = false; selectedChoiceId = null;" 
                                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg font-medium transition"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            const form = document.getElementById('endWagerForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Ending...';
                    
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            winning_choice_id: formData.get('winning_choice_id')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.reload();
                            }
                        } else {
                            throw new Error(data.message || 'Failed to end wager');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'An error occurred while ending the wager');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
