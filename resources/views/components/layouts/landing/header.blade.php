<header class="w-full bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-700 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-gem text-2xl text-slate-400"></i>
            <span class="font-extrabold text-xl text-slate-700 tracking-tight drop-shadow">SIP Perhiasan</span>
        </div>
        <ul class="flex gap-6 font-semibold text-slate-700 dark:text-slate-200 text-base items-center">
            <li><a href="/" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-house"></i> Beranda</a></li>
            <li><a href="#produk" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-ring"></i> Produk</a></li>
            <li><a href="{{ route('custom') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-wand-magic-sparkles"></i> Custom Request</a></li>
            <li><a href="{{ route('about') }}" class="hover:text-indigo-500 transition flex items-center gap-1"><i class="fa-solid fa-circle-info"></i> Tentang</a></li>
        </ul>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</header>