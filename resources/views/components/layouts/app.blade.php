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

<body class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{ $slot }}

    @fluxScripts
    <script src="{{ asset('js/theme-manager.js') }}"></script>
</body>

</html>