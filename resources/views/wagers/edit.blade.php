<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 transition-colors">
        <div class="container mx-auto px-4 py-12 max-w-5xl">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Edit Wager</h2>

                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="editWagerForm" action="{{ route('wagers.update', $wager) }}" method="POST"
                            class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Wager Name -->
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Wager Name *
                                </label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $wager->name) }}"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                    required maxlength="255">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white">{{ old('description', $wager->description) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Max Players -->
                                <div>
                                    <label for="max_players"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Max Players *
                                    </label>
                                    <input type="number" id="max_players" name="max_players" min="2"
                                        max="100" value="{{ old('max_players', $wager->max_players) }}"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                        required>
                                </div>

                                <!-- Privacy -->
                                <div>
                                    <label for="privacy"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Privacy *
                                    </label>
                                    <select id="privacy" name="privacy"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                        required>
                                        <option value="public"
                                            {{ old('privacy', $wager->privacy) === 'public' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="private"
                                            {{ old('privacy', $wager->privacy) === 'private' ? 'selected' : '' }}>
                                            Private
                                        </option>
                                    </select>
                                </div>

                                <!-- Starting Time -->
                                <div>
                                    <label for="starting_time"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Start Time *
                                    </label>
                                    <input type="datetime-local" id="starting_time" name="starting_time"
                                        value="{{ old('starting_time', \Carbon\Carbon::parse($wager->starting_time)->format('Y-m-d\TH:i')) }}"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                        required>
                                </div>

                                <!-- End Time -->
                                <div>
                                    <label for="ending_time"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        End Time *
                                    </label>
                                    <input type="datetime-local" id="ending_time" name="ending_time"
                                        value="{{ old('ending_time', \Carbon\Carbon::parse($wager->ending_time)->format('Y-m-d\TH:i')) }}"
                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                        required>
                                </div>
                            </div>

                            <!-- Choices -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Choices * (minimum 2, maximum 10)
                                    </label>
                                    <button type="button" onclick="addChoice()" id="addChoiceBtn"
                                        class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium transition-opacity">
                                        + Add Choice
                                    </button>
                                </div>

                                <div id="choicesContainer" class="space-y-2">
                                    @php
                                        $oldChoices = old('choices', []);
                                        if (!empty($oldChoices)) {
                                            $choices = $oldChoices;
                                        } else {
                                            $choices = $wager->choices ?? [];
                                        }
                                    @endphp

                                    @if (empty($choices) || count($choices) == 0)
                                        <!-- Default 2 empty choices if none exist -->
                                        @for ($i = 0; $i < 2; $i++)
                                            <div class="flex items-center space-x-2 choice-item">
                                                <input type="hidden" name="choices[{{ $i }}][id]"
                                                    value="">
                                                <input type="hidden" name="choices[{{ $i }}][total_bet]"
                                                    value="0">
                                                <input type="text" name="choices[{{ $i }}][label]"
                                                    value="" placeholder="Enter choice text"
                                                    class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                                    required>
                                                <button type="button" onclick="removeChoice(this)"
                                                    class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endfor
                                    @else
                                        @foreach ($choices as $index => $choice)
                                            @php
                                                $choiceId = is_array($choice)
                                                    ? $choice['id'] ?? ''
                                                    : (is_object($choice)
                                                        ? $choice->id
                                                        : '');
                                                $choiceLabel = is_array($choice)
                                                    ? $choice['label'] ?? ''
                                                    : (is_object($choice)
                                                        ? $choice->label
                                                        : '');
                                                $totalBet = is_array($choice)
                                                    ? $choice['total_bet'] ?? 0
                                                    : (is_object($choice)
                                                        ? $choice->total_bet
                                                        : 0);
                                            @endphp
                                            <div class="flex items-center space-x-2 choice-item">
                                                <input type="hidden" name="choices[{{ $index }}][id]"
                                                    value="{{ $choiceId }}">
                                                <input type="hidden" name="choices[{{ $index }}][total_bet]"
                                                    value="{{ $totalBet }}">
                                                <input type="text" name="choices[{{ $index }}][label]"
                                                    value="{{ $choiceLabel }}" placeholder="Enter choice text"
                                                    class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                                                    required>
                                                <button type="button" onclick="removeChoice(this)"
                                                    class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Form Actions -->
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
    </div>

    @push('scripts')
        <script>
            function getChoiceCount() {
                return document.querySelectorAll('.choice-item').length;
            }

            function addChoice() {
                const container = document.getElementById('choicesContainer');
                const count = getChoiceCount();

                if (count >= 10) {
                    alert('Maximum 10 choices allowed');
                    return;
                }

                const newChoice = document.createElement('div');
                newChoice.className = 'flex items-center space-x-2 choice-item';
                newChoice.innerHTML = `
                <input type="hidden" name="choices[${count}][id]" value="">
                <input type="hidden" name="choices[${count}][total_bet]" value="0">
                <input type="text" name="choices[${count}][label]" 
                    value=""
                    placeholder="Enter choice text"
                    class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white"
                    required>
                <button type="button" onclick="removeChoice(this)"
                    class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;

                container.appendChild(newChoice);
                newChoice.querySelector('input[type="text"]').focus();
                updateButtons();
            }

            function removeChoice(button) {
                const count = getChoiceCount();

                if (count <= 2) {
                    alert('Minimum 2 choices required');
                    return;
                }

                const item = button.closest('.choice-item');
                item.remove();
                reindexChoices();
                updateButtons();
            }

            function reindexChoices() {
                const items = document.querySelectorAll('.choice-item');
                items.forEach((item, index) => {
                    const inputs = item.querySelectorAll('input');
                    inputs[0].name = `choices[${index}][id]`;
                    inputs[1].name = `choices[${index}][total_bet]`;
                    inputs[2].name = `choices[${index}][label]`;
                });
            }

            function updateButtons() {
                const count = getChoiceCount();
                const addBtn = document.getElementById('addChoiceBtn');
                const removeButtons = document.querySelectorAll('.choice-item button');

                if (count >= 10) {
                    addBtn.disabled = true;
                    addBtn.style.opacity = '0.5';
                } else {
                    addBtn.disabled = false;
                    addBtn.style.opacity = '1';
                }

                removeButtons.forEach(btn => {
                    if (count <= 2) {
                        btn.disabled = true;
                        btn.style.opacity = '0.5';
                    } else {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateButtons();
            });
        </script>
    @endpush
</x-app-layout>
