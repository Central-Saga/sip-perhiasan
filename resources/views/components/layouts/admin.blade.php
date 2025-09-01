<x-layouts.app :title="$title ?? null">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @if (!request()->routeIs('settings.*'))
        <x-layouts.admin.sidebar />
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <flux:main class="flex-1">
                {{ $slot }}
            </flux:main>
        </div>
    </div>
</x-layouts.app>