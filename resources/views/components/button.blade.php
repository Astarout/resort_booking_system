
{{-- Button Component --}}

@props([
    'type' => 'button',
    'variant' => 'primary',
])

@php

    $base = "px-4 py-2 rounded-2xl font-semibold transition";

    $variants = [
        'primary'   => 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white active:scale-95',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 active:bg-gray-800 text-white active:scale-95',
        'tertiary' => 'bg-green-600 hover:bg-green-700 active:bg-green-800 text-white active:scale-95',
        'danger'    => 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white active:scale-95',
        'transparent' => 'bg-transparent hover:bg-gray-200 active:bg-gray-300 text-gray-800 active:scale-95',
    ];

    $classes = $variants[$variant] ?? $variants['primary'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base $classes"]) }}>
    {{ $slot }}
</button>
