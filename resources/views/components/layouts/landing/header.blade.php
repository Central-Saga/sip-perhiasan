<nav class="w-full bg-[#F3F3E0] shadow-lg fixed top-0 left-0 z-50">
    <div class="max-w-6xl mx-auto flex items-center justify-between py-5 px-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-xl font-bold text-[#608BC1] tracking-wide">Pondok Putri</a>
        </div>
        <div class="flex gap-6 items-center">
            <a href="{{ route('home') }}" class="text-[#608BC1] hover:text-pink-500 font-semibold transition duration-150">Home</a>
            <a href="{{ route('produk') }}" class="text-[#608BC1] hover:text-pink-500 font-semibold transition duration-150">Produk</a>
            <a href="{{ route('about') }}" class="text-[#608BC1] hover:text-pink-500 font-semibold transition duration-150">About Us</a>
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-lg bg-[#608BC1] text-white font-bold shadow hover:bg-[#4a6a99] transition duration-150">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg bg-[#608BC1] text-white font-bold shadow hover:bg-[#4a6a99] transition duration-150">Login</a>
            @endauth
        </div>
    </div>
</nav>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
