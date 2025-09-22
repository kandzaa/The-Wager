@props([
    'choices' => [],
    'label' => 'Choices',
    'name' => 'choices',
])

<div class="space-y-2 choices-container" data-name="{{ $name }}">
    <x-input-label :value="$label" />

    <div class="choices-list">
        @if (count($choices) > 0)
            @foreach ($choices as $index => $choice)
                <div class="flex items-center mt-2">
                    <x-text-input name="{{ $name }}[{{ $index }}]" :value="old($name . '.' . $index, is_array($choice) ? $choice['label'] ?? '' : $choice)" class="block w-full"
                        required />
                    @if ($loop->index > 0 || count($choices) > 1)
                        <button type="button"
                            class="ml-2 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 remove-choice">
                            {{ __('Delete') }}
                        </button>
                    @endif
                </div>
            @endforeach
        @else
            <div class="flex items-center mt-2">
                <x-text-input name="{{ $name }}[0]" class="block w-full" required />
            </div>
        @endif
    </div>

    <x-primary-button type="button" class="mt-2 add-choice">
        {{ __('Add Choice') }}
    </x-primary-button>

    <x-input-error :messages="$errors->get($name . '.*')" class="mt-2" />
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.choices-container').forEach(container => {
                const choicesList = container.querySelector('.choices-list');
                const addButton = container.querySelector('.add-choice');
                const name = container.dataset.name;
                let choiceCount = choicesList.children.length;

                // atjaunini choices
                function updateChoiceIndices() {
                    const inputs = choicesList.querySelectorAll('input[type="text"]');
                    inputs.forEach((input, index) => {
                        input.name = `${name}[${index}]`;
                    });
                    choiceCount = inputs.length;
                }

                // pieliec jaunu choice
                addButton.addEventListener('click', function() {
                    const newChoice = document.createElement('div');
                    newChoice.className = 'flex items-center mt-2';
                    newChoice.innerHTML = `
                <input type="text" 
                    name="${name}[${choiceCount}]" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                <button type="button" 
                    class="ml-2 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 remove-choice">
                    Delete
                </button>
            `;

                    choicesList.appendChild(newChoice);
                    choiceCount++;

                    const input = newChoice.querySelector('input');
                    if (input) input.focus();
                });

                // noņem choice
                container.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-choice')) {
                        e.preventDefault();
                        const choiceItem = e.target.closest('.flex.items-center');
                        if (choiceItem) {
                            // nolasa ja ir viens choice, tad nenoņem
                            if (choicesList.children.length > 1) {
                                choiceItem.remove();
                                updateChoiceIndices();
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
