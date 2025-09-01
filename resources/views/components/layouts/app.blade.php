<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <!-- Theme initialization script to prevent flash -->
    <script>
        (function() {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                let actualTheme = savedTheme;

                if (savedTheme === 'system') {
                    actualTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                // Apply theme immediately to prevent flash
                document.documentElement.classList.remove('light', 'dark');
                document.documentElement.classList.add(actualTheme);

                // Force theme application
                document.documentElement.setAttribute('data-theme', actualTheme);

                // Override any inline styles
                if (actualTheme === 'light') {
                    document.documentElement.style.colorScheme = 'light';
                    document.documentElement.style.setProperty('--color-scheme', 'light', 'important');
                } else {
                    document.documentElement.style.colorScheme = 'dark';
                    document.documentElement.style.setProperty('--color-scheme', 'dark', 'important');
                }

                // Ensure theme persists
                setInterval(() => {
                    const currentTheme = localStorage.getItem('theme') || 'dark';
                    let expectedTheme = currentTheme;

                    if (currentTheme === 'system') {
                        expectedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    }

                    if (!document.documentElement.classList.contains(expectedTheme)) {
                        document.documentElement.classList.remove('light', 'dark');
                        document.documentElement.classList.add(expectedTheme);
                        document.documentElement.setAttribute('data-theme', expectedTheme);
                    }
                }, 100);
            })();
    </script>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{ $slot }}

    @fluxScripts
    <script src="{{ asset('js/theme-manager.js') }}"></script>
</body>

</html>