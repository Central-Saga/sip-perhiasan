@props([
    'sortable' => false,
    'direction' => null,
])

<th scope="col" {{ $attributes->merge(['class' => 'px-3 py-3.5 text-left text-sm font-semibold text-gray-900']) }}>
    @if ($sortable)
        <button type="button" {{ $attributes->except('class') }} class="group inline-flex">
            {{ $slot }}
            <span class="ml-2 flex-none rounded">
                @if ($direction === 'asc')
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @elseif ($direction === 'desc')
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="h-5 w-5 text-gray-400 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @endif
            </span>
        </button>
    @else
        <span class="inline-flex">{{ $slot }}</span>
    @endif
</th>
