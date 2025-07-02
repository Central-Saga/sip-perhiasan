<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Perhiasan')</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 text-[#1b1b18] min-h-screen flex flex-col">
    <nav class="w-full fixed top-0 left-0 z-50 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-yellow-100 dark:border-yellow-900 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" fill="#fef9c3" stroke="#facc15" stroke-width="2"/><ellipse cx="24" cy="30" rx="14" ry="8" fill="#fde68a" opacity=".3"/><ellipse cx="24" cy="22" rx="10" ry="12" fill="#fef08a"/><circle cx="24" cy="20" r="8" fill="#facc15"/><ellipse cx="24" cy="26" rx="6" ry="3" fill="#f59e42"/></svg>
                <span class="font-extrabold text-xl text-yellow-600 tracking-tight drop-shadow">SIP Perhiasan</span>
            </div>
            <ul class="flex gap-6 font-semibold text-yellow-700 dark:text-yellow-300 text-base items-center">
                <li><a href="/" class="hover:text-yellow-500 transition">Beranda</a></li>
                <li><a href="{{ route('katalog') }}" class="hover:text-yellow-500 transition">Katalog Produk</a></li>
            </ul>
            <div class="flex items-center gap-2 ml-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-yellow-400 text-yellow-700 dark:text-yellow-200 hover:bg-yellow-100 dark:hover:bg-yellow-700/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                        Profil
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-yellow-400 text-yellow-700 dark:text-yellow-200 hover:bg-yellow-100 dark:hover:bg-yellow-700/20 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-yellow-400 text-white font-bold hover:bg-yellow-500 transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>
    <main class="pt-32 flex-1">
        @yield('content')
    </main>
    <footer class="w-full bg-gradient-to-r from-yellow-50 via-yellow-100 to-yellow-200 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 border-t border-yellow-100 dark:border-yellow-900 mt-24 py-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6 gap-4">
            <div class="flex items-center gap-2">
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" fill="#fef9c3" stroke="#facc15" stroke-width="2"/></svg>
                <span class="font-bold text-yellow-700 dark:text-yellow-300">SIP Perhiasan</span>
            </div>
            <div class="text-yellow-700 dark:text-yellow-200 text-sm">&copy; {{ date('Y') }} SIP Perhiasan. All rights reserved.</div>
        </div>
    </footer>
</body>
</html>
