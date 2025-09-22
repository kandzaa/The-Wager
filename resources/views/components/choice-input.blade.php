@props([
    'value' => '',
    'index' => 0,
])

<div class="flex items-center mt-2">
    <x-text-input name="choices[{{ $index }}]" :value="old('choices.' . $index, $value)" class="block w-full" required />
    <button type="button"
        class="ml-2 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 remove-choice">
        {{ __('Delete') }}
    </button>
</div>
