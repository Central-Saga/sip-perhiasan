@props(['href' => '#'])

<a {{ $attributes->merge(['class' => 'text-indigo-600 hover:text-indigo-900', 'href' => $href]) }}>
    {{ $slot }}
</a>
