<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

    <!-- Hero Animations -->
    <script src="{{ asset('js/hero-animations.js') }}"></script>

    <!-- Product Animations -->
    <script src="{{ asset('js/product-animations.js') }}"></script>

    <!-- About Animations -->
    <script src="{{ asset('js/about-animations.js') }}"></script>

    <!-- Theme Manager -->
    <script src="{{ asset('js/theme-manager.js') }}"></script>

    <!-- Cart Manager -->
    <script src="{{ asset('js/cart-manager.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white dark:bg-zinc-900 flex flex-col">
        @include('components.layouts.landing.header')

        <!-- Page Content -->
        <main class="flex-1 pt-16">
            <flux:main class="!p-0">
                {{ $slot }}
            </flux:main>
        </main>

        @include('components.layouts.landing.footer')

        @fluxScripts
    </div>
</body>

</html>