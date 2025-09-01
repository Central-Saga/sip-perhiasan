<x-layouts.app :title="$title ?? null">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-layouts.admin.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.app>