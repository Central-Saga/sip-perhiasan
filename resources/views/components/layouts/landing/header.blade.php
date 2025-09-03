<nav class="w-full fixed top-0 left-0 z-50 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-700 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-gem text-2xl text-slate-400"></i>
            <a href="/" class="font-extrabold text-xl text-slate-700 dark:text-slate-200 tracking-tight drop-shadow">SIP Perhiasan</a>
        </div>

        <ul class="flex gap-6 font-semibold text-slate-700 dark:text-slate-200 text-base items-center">
            <li><a href="/" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-house"></i> Beranda</a></li>
            <li><a href="{{ route('produk') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-ring"></i> Produk</a></li>
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
                        <i class="fa-solid fa-user"></i> {{ $user?->name ?? 'Profil' }}
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-slate-400 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition flex items-center gap-2"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-indigo-500 text-white font-bold hover:bg-indigo-600 transition flex items-center gap-2"><i class="fa-solid fa-user-plus"></i> Daftar</a>
            @endauth
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</nav>

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

// Initialize theme on load for landing layout
(function() {
    const saved = localStorage.getItem('theme');
    if(saved === 'dark' || (saved === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        if(saved === null) localStorage.setItem('theme', 'dark');
    }
})();

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
</script>
