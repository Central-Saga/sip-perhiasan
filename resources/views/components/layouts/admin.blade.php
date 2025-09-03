<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <!-- Theme initialization script to prevent flash -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            const initialTheme = savedTheme === 'system'
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : savedTheme;

            const html = document.documentElement;
            html.classList.remove('light', 'dark');
            html.classList.add(initialTheme);
            html.setAttribute('data-theme', initialTheme);
            html.style.colorScheme = initialTheme;
        })();
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Mobile Header -->
    <flux:header class="lg:hidden border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0"
            wire:navigate>
            <x-app-logo />
        </a>

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <div class="flex min-h-screen">
        <!-- Desktop Sidebar -->
        @if (!request()->routeIs('settings.*'))
        <x-layouts.admin.sidebar />
        @endif

        <!-- Mobile Sidebar -->
        @if (!request()->routeIs('settings.*'))
        <flux:sidebar stashable
            class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse"
                wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @unlessrole('Pelanggan')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </flux:navlist.item>

                    @can('mengelola user')
                    <flux:navlist.item icon="users" :href="route('user.index')" :current="request()->routeIs('user.*')">
                        {{ __('User') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola role')
                    <flux:navlist.item icon="shield-check" :href="route('role.index')"
                        :current="request()->routeIs('role.*')">
                        {{ __('Role') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola pelanggan')
                    <flux:navlist.item icon="users" :href="route('pelanggan.index')"
                        :current="request()->routeIs('pelanggan.*')">
                        {{ __('Pelanggan') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola produk')
                    <flux:navlist.item icon="sparkles" :href="route('produk.index')"
                        :current="request()->routeIs('produk.*')">
                        {{ __('Produk') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola transaksi')
                    <flux:navlist.item icon="shopping-cart" :href="route('transaksi.index')"
                        :current="request()->routeIs('transaksi.*')">
                        {{ __('Transaksi') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola pengiriman')
                    <flux:navlist.item icon="paper-airplane" :href="route('pengiriman.index')"
                        :current="request()->routeIs('pengiriman.*')">
                        {{ __('Pengiriman') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola pembayaran')
                    <flux:navlist.item icon="currency-dollar" :href="route('pembayaran.index')"
                        :current="request()->routeIs('pembayaran.*')">
                        {{ __('Pembayaran') }}
                    </flux:navlist.item>
                    @endcan

                    @can('mengelola custom request')
                    <flux:navlist.item icon="document-text" :href="route('custom-request.index')"
                        :current="request()->routeIs('custom-request.*')">
                        {{ __('Custom Request') }}
                    </flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endunlessrole
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                    target="_blank">
                    {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                    target="_blank">
                    {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>
        @endif

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0">
            <flux:main class="p-4">
                {{ $slot }}
            </flux:main>
        </div>
    </div>

    @fluxScripts
    <script src="{{ asset('js/theme-manager.js') }}"></script>
</body>

</html>