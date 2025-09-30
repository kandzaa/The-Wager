@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Edit Wager</h2>

                    <form id="editWagerForm" action="{{ route('wagers.update', $wager) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Theme *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $wager->name) }}"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                required maxlength="255">
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white">{{ old('description', $wager->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="max_players"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Max Players *
                                </label>
                                <input type="number" id="max_players" name="max_players" min="2" max="100"
                                    value="{{ old('max_players', $wager->max_players) }}"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                    required>
                                @error('max_players')
                                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ending_time"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    End Time *
                                </label>
                                <input type="datetime-local" id="ending_time" name="ending_time"
                                    value="{{ old('ending_time', \Carbon\Carbon::parse($wager->ending_time)->format('Y-m-d\TH:i')) }}"
                                    min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                    required>
                                @error('ending_time')
                                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Choices *
                                </label>
                                <button type="button" id="addChoiceBtn"
                                    class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium">
                                    + Add Choice
                                </button>
                            </div>

                            <div id="choicesContainer" class="space-y-2">
                                @foreach (old('choices', $wager->choices) as $index => $choice)
                                    <div class="flex items-center space-x-2 choice-item">
                                        <input type="hidden" name="choices[{{ $index }}][id]"
                                            value="{{ is_array($choice) ? $choice['id'] ?? '' : $choice->id }}">
                                        <input type="text" name="choices[{{ $index }}][label]"
                                            value="{{ is_array($choice) ? $choice['label'] ?? '' : $choice->label }}"
                                            class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                            required>
                                        <button type="button" class="remove-choice text-rose-500 hover:text-rose-700"
                                            {{ $wager->choices->count() <= 2 ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('choices.' . $index . '.label')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                @endforeach
                            </div>
                            @error('choices')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="{{ route('wagers.show', $wager) }}"
                                class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const choicesContainer = document.getElementById('choicesContainer');
                const addChoiceBtn = document.getElementById('addChoiceBtn');
                let choiceCount = {{ count(old('choices', $wager->choices)) }};

                // Add new choice
                addChoiceBtn.addEventListener('click', function() {
                    if (choiceCount >= 10) return;

                    const choiceItem = document.createElement('div');
                    choiceItem.className = 'flex items-center space-x-2 choice-item';
                    choiceItem.innerHTML = `
                <input type="hidden" name="choices[${choiceCount}][id]" value="">
                <input type="text" name="choices[${choiceCount}][label]" 
                    class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                    required>
                <button type="button" class="remove-choice text-rose-500 hover:text-rose-700"
                    ${choiceCount < 2 ? 'disabled' : ''}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;

                    choicesContainer.appendChild(choiceItem);
                    choiceCount++;

                    updateRemoveButtons();
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-choice')) {
                        const choiceItem = e.target.closest('.choice-item');
                        if (choicesContainer.querySelectorAll('.choice-item').length <= 2) return;

                        choiceItem.remove();
                        choiceCount--;

                        const choiceItems = choicesContainer.querySelectorAll('.choice-item');
                        choiceItems.forEach((item, index) => {
                            const inputs = item.querySelectorAll('input');
                            inputs[0].name = `choices[${index}][id]`;
                            inputs[1].name = `choices[${index}][label]`;
                        });

                        updateRemoveButtons();
                    }
                });

                function updateRemoveButtons() {
                    const removeButtons = document.querySelectorAll('.remove-choice');
                    const choiceItems = choicesContainer.querySelectorAll('.choice-item');

                    removeButtons.forEach(button => {
                        button.disabled = choiceItems.length <= 2;
                    });

                    addChoiceBtn.disabled = choiceCount >= 10;
                }

                updateRemoveButtons();
            });
        </script>
    @endpush
@endsection
