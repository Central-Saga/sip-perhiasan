<nav
    class="w-full fixed top-0 left-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-700/50 shadow-lg">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-4">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center">
                <i class="fa-solid fa-gem text-white text-lg"></i>
            </div>
            <div>
                <a href="/" class="font-black text-xl text-slate-800 dark:text-slate-100 tracking-tight">
                    Bliss Silversmith
                </a>
                <p class="text-xs text-slate-500 dark:text-slate-400 -mt-1">Premium Silver Collection</p>
            </div>
        </div>

        <ul class="hidden md:flex gap-8 font-semibold text-slate-700 dark:text-slate-200 text-base items-center">
            <li><a href="/"
                    class="hover:text-purple-500 transition-all duration-300 flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20">
                    <i class="fa-solid fa-house text-sm"></i>
                    <span>Beranda</span>
                </a></li>
            <li><a href="{{ route('produk') }}"
                    class="hover:text-purple-500 transition-all duration-300 flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20">
                    <i class="fa-solid fa-gem text-sm"></i>
                    <span>Produk</span>
                </a></li>
            <li><a href="{{ route('custom') }}"
                    class="hover:text-purple-500 transition-all duration-300 flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                    <span>Custom Request</span>
                </a></li>
            <li><a href="{{ route('about') }}"
                    class="hover:text-purple-500 transition-all duration-300 flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20">
                    <i class="fa-solid fa-circle-info text-sm"></i>
                    <span>Tentang</span>
                </a></li>
        </ul>

        <div class="flex items-center gap-3">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn"
                class="md:hidden p-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <!-- Theme Toggle -->
            <button onclick="toggleDarkMode()"
                class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition-all duration-300"
                title="Toggle Dark/Light Mode">
                <i class="fa-solid fa-moon dark:hidden text-sm"></i>
                <i class="fa-solid fa-sun hidden dark:block text-sm"></i>
            </button>

            <!-- Cart -->
            <a href="{{ route('cart') }}" id="cartBtn"
                class="relative p-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition-all duration-300">
                <i class="fa-solid fa-cart-shopping text-sm"></i>
                <span id="cartCount"
                    class="absolute -top-1 -right-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">0</span>
            </a>

            <!-- Auth Buttons -->
@auth
    @php($user = auth()->user())
    @if($user && $user->hasRole('Pelanggan'))
        <div class="relative">
            <button id="userMenuBtn"
                class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-400 text-slate-700 dark:text-slate-200 bg-white/50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-700/30 transition">
                <i class="fa-solid fa-user"></i>
                <span class="max-w-[140px] truncate">{{ $user->name }}</span>
                <i class="fa-solid fa-chevron-down text-xs opacity-70"></i>
            </button>
            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg overflow-hidden z-[60]">
                <a href="{{ route('cart') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/60">
                    <i class="fa-solid fa-cart-shopping"></i> Keranjang
                </a>
                <a href="{{ route('transaksi') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/60">
                    <i class="fa-solid fa-receipt"></i> Transaksi
                </a>
                <div class="h-px bg-slate-200 dark:bg-slate-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-400 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition">
            <i class="fa-solid fa-user"></i> {{ $user?->name ?? 'Dashboard' }}
        </a>
    @endif
@else
    <a href="{{ route('login') }}"
        class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition-all duration-300">
        <i class="fa-solid fa-right-to-bracket text-sm"></i>
        <span>Masuk</span>
    </a>
    <a href="{{ route('register') }}"
        class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl">
        <i class="fa-solid fa-user-plus text-sm"></i>
        <span class="hidden sm:inline">Daftar</span>
    </a>
@endauth
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</nav>

<!-- Mobile Menu -->
<div id="mobileMenu"
    class="fixed top-16 left-0 w-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-700/50 shadow-lg z-40 hidden md:hidden">
    <div class="px-4 py-4 space-y-2">
        <a href="/"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
            <i class="fa-solid fa-house text-sm"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('produk') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
            <i class="fa-solid fa-gem text-sm"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('custom') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
            <span>Custom Request</span>
        </a>
        <a href="{{ route('about') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
            <i class="fa-solid fa-circle-info text-sm"></i>
            <span>Tentang</span>
        </a>

        <!-- Mobile Auth Buttons -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700 space-y-2">
            @auth
            @php($user = auth()->user())
            @if($user && $user->hasRole('Pelanggan'))
            <a href="{{ route('transaksi') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
                <i class="fa-solid fa-receipt text-sm"></i>
                <span>Transaksi Saya</span>
            </a>
            @else
            <a href="{{ url('/dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
                <i class="fa-solid fa-gauge-high text-sm"></i>
                <span>Dashboard</span>
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all duration-300">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    <span>Keluar</span>
                </button>
            </form>
            @else
            <a href="{{ route('login') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
                <i class="fa-solid fa-right-to-bracket text-sm"></i>
                <span>Masuk</span>
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold hover:from-purple-600 hover:to-indigo-600 transition-all duration-300">
                <i class="fa-solid fa-user-plus text-sm"></i>
                <span>Daftar</span>
            </a>
            @endauth
        </div>
    </div>
</div>

<script>
    // Dark mode toggle for landing layout
    function toggleDarkMode() {
        if(document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Mobile menu toggle
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');

        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            mobileMenuBtn.innerHTML = '<i class="fa-solid fa-times text-lg"></i>';
        } else {
            mobileMenu.classList.add('hidden');
            mobileMenuBtn.innerHTML = '<i class="fa-solid fa-bars text-lg"></i>';
        }
    }

// Simple dropdown for pelanggan menu
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('userMenuBtn');
    const menu = document.getElementById('userMenu');
    if(!btn || !menu) return;
    btn.addEventListener('click', function(e){
        e.stopPropagation();
        menu.classList.toggle('hidden');
    });
    document.addEventListener('click', function(){
        if(!menu.classList.contains('hidden')) menu.classList.add('hidden');
    });
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && !menu.classList.contains('hidden')) menu.classList.add('hidden');
    });
});

    // Initialize theme on load for landing layout
    (function() {
        const saved = localStorage.getItem('theme');
        if(saved === 'dark' || (saved === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            if(saved === null) localStorage.setItem('theme', 'dark');
        }
    })();

    // Add event listener for mobile menu
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        }
    });
</script>
