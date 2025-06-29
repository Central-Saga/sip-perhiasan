@props(['even' => false])

<tr {{ $attributes->merge(['class' => $even ? 'bg-gray-50' : 'bg-white']) }}>
    {{ $slot }}
</tr>
