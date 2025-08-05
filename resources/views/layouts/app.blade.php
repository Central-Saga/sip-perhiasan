<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Perhiasan')</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-100 via-white to-slate-200 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 text-[#23272f] min-h-screen flex flex-col">
    <nav class="w-full fixed top-0 left-0 z-50 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gem text-2xl text-slate-400"></i>
                <span class="font-extrabold text-xl text-slate-700 tracking-tight drop-shadow">SIP Perhiasan</span>
            </div>
            <ul class="flex gap-6 font-semibold text-slate-700 dark:text-slate-200 text-base items-center">
                <li><a href="/" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-house"></i> Beranda</a></li>
                <li><a href="{{ route('produk.landing') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-ring"></i> Produk</a></li>
                <li><a href="{{ route('custom') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-wand-magic-sparkles"></i> Custom Request</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-circle-info"></i> Tentang</a></li>
            </ul>
            <div class="flex items-center gap-2 ml-4">
                <button onclick="toggleDarkMode()" class="px-2 py-2 rounded-full border border-slate-300 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition" title="Toggle Dark/Light Mode">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:block"></i>
                </button>
                <a href="{{ route('cart') }}" id="cartBtn" class="relative flex items-center gap-2 px-3 py-2 rounded-full border border-slate-300 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-indigo-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">0</span>
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-400 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition">
                        <i class="fa-solid fa-user"></i> Profil
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-slate-400 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition flex items-center gap-2"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition flex items-center gap-2"><i class="fa-solid fa-user-plus"></i> Daftar</a>
                @endauth
            </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    </nav>
    <main class="pt-10 flex-1">
        @yield('content')
    </main>
    <footer class="w-full bg-gradient-to-r from-slate-100 via-white to-slate-200 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 border-t border-slate-200 dark:border-slate-700  py-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6 gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gem text-xl text-slate-400"></i>
                <span class="font-bold text-slate-700 dark:text-slate-200">SIP Perhiasan</span>
            </div>
            <div class="flex gap-4 text-slate-700 dark:text-slate-200 text-sm items-center">
                <a href="{{ route('produk.landing') }}" class="hover:underline">Produk</a>
                <a href="{{ route('about') }}" class="hover:underline">Tentang</a>
                <a href="#kontak" class="hover:underline">Kontak</a>
            </div>
            <div class="text-slate-700 dark:text-slate-200 text-sm">&copy; {{ date('Y') }} SIP Perhiasan. All rights reserved.</div>
        </div>
    </footer>

    <script>
    // Dark mode toggle function
    function toggleDarkMode() {
        if(document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
    
    // On load, set theme from localStorage
    if(localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (localStorage.getItem('theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        // If no theme preference, use system preference
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
    </script>
</body>
</html>
