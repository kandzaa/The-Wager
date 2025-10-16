@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-green-600 ring-0 rounded-md shadow-sm']) }}>
