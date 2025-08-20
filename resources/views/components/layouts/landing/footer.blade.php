<footer class="w-full bg-gradient-to-r from-slate-100 via-white to-slate-200 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 border-t border-slate-200 dark:border-slate-700  py-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6 gap-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-gem text-xl text-slate-400"></i>
            <span class="font-bold text-slate-700 dark:text-slate-200">SIP Perhiasan</span>
        </div>
        <div class="flex gap-4 text-slate-700 dark:text-slate-200 text-sm items-center">
            <a href="#produk" class="hover:underline">Produk</a>
            <a href="{{ route('about') }}" class="hover:underline">Tentang</a>
            <a href="#kontak" class="hover:underline">Kontak</a>
        </div>
        <div class="text-slate-700 dark:text-slate-200 text-sm">&copy; {{ date('Y') }} SIP Perhiasan. All rights reserved.</div>
    </div>
</footer>