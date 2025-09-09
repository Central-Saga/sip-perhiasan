@props(['type' => 'default'])

@php
    $classes = match ($type) {
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800'
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ' . $classes]) }}>
    {{ $slot }}
</span>
