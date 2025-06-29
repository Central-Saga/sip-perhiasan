<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIP Perhiasan - Toko Perhiasan Modern</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-bg {
            background: linear-gradient(120deg, #fef9c3 0%, #fef6e4 60%, #fffbe8 100%);
            position: relative;
        }
        body { font-family: 'Instrument Sans', sans-serif; }
        .glass {
            background: rgba(255,255,255,0.88);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(6px);
            border-radius: 1.5rem;
            border: 1.5px solid #facc15;
        }
        @media (prefers-color-scheme: dark) {
            .glass {
                background: rgba(30,41,59,0.88);
                border: 1.5px solid #facc15;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 text-[#1b1b18] min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="w-full fixed top-0 left-0 z-50 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-yellow-100 dark:border-yellow-900 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" fill="#fef9c3" stroke="#facc15" stroke-width="2"/><ellipse cx="24" cy="30" rx="14" ry="8" fill="#fde68a" opacity=".3"/><ellipse cx="24" cy="22" rx="10" ry="12" fill="#fef08a"/><circle cx="24" cy="20" r="8" fill="#facc15"/><ellipse cx="24" cy="26" rx="6" ry="3" fill="#f59e42"/></svg>
                <span class="font-extrabold text-xl text-yellow-600 tracking-tight drop-shadow">SIP Perhiasan</span>
            </div>
            <ul class="flex gap-6 font-semibold text-yellow-700 dark:text-yellow-300 text-base">
                <li><a href="#" class="hover:text-yellow-500 transition">Beranda</a></li>
                <li><a href="#produk" class="hover:text-yellow-500 transition">Produk</a></li>
                <li><a href="#promo" class="hover:text-yellow-500 transition">Promo</a></li>
                <li><a href="#testimoni" class="hover:text-yellow-500 transition">Testimoni</a></li>
                <li><a href="#layanan" class="hover:text-yellow-500 transition">Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg pt-40 pb-20 min-h-[90vh] flex items-center justify-center text-center px-4 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-yellow-100 rounded-full opacity-30 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-yellow-200 rounded-full opacity-30 blur-3xl animate-pulse"></div>
        <div class="absolute left-1/2 top-1/3 -translate-x-1/2 -z-10 w-96 h-32 bg-yellow-50 rounded-full blur-2xl opacity-40 animate-pulse"></div>
        <div class="max-w-4xl z-10">
            <div class="mb-6 flex justify-center">
                <!-- Ornamen perhiasan SVG -->
                <svg width="140" height="140" viewBox="0 0 220 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="110" cy="110" r="100" fill="#fef9c3"/>
                    <ellipse cx="110" cy="140" rx="60" ry="40" fill="#fde68a" opacity=".15"/>
                    <ellipse cx="110" cy="110" rx="50" ry="60" fill="#fef08a"/>
                    <circle cx="110" cy="100" r="40" fill="#facc15"/>
                    <ellipse cx="110" cy="120" rx="30" ry="18" fill="#f59e42"/>
                    <ellipse cx="110" cy="90" rx="18" ry="20" fill="#fff"/>
                    <ellipse cx="100" cy="95" rx="4" ry="6" fill="#facc15"/>
                    <ellipse cx="120" cy="95" rx="4" ry="6" fill="#facc15"/>
                    <rect x="98" y="110" width="24" height="8" rx="4" fill="#fff"/>
                    <rect x="104" y="112" width="12" height="4" rx="2" fill="#facc15"/>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-yellow-700 dark:text-yellow-400 drop-shadow-lg leading-tight">Perhiasan Maskulin Modern</h1>
            <p class="text-lg md:text-xl max-w-2xl text-yellow-800 dark:text-yellow-200 mb-6 mx-auto">Bukan sekadar aksesoris — ini tentang gaya, kepercayaan diri, dan identitas Anda.</p>
            <a href="#produk" class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded-full text-lg shadow-lg hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition">Mulai Belanja</a>
            <p class="mt-6 text-yellow-700 dark:text-yellow-300 italic text-sm">✨ Dipercaya oleh 3.000+ pria di seluruh Indonesia</p>
        </div>
    </section>

    <!-- Menu Utama Pelanggan -->
    <section class="max-w-6xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
        <div class="glass p-8 flex flex-col items-center text-center border border-yellow-200 dark:border-yellow-700 shadow-lg hover:shadow-yellow-400/60 hover:scale-105 transition">
            <span class="mb-3">
                <!-- SVG promo gold -->
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#facc15" opacity=".15"/><path fill="#f59e42" d="M12 6.5c-2.48 0-4.5 2.02-4.5 4.5s2.02 4.5 4.5 4.5 4.5-2.02 4.5-4.5-2.02-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5S10.62 8.5 12 8.5s2.5 1.12 2.5 2.5S13.38 13.5 12 13.5z"/></svg>
            </span>
            <h3 class="font-bold text-xl mb-2 text-yellow-700 dark:text-yellow-300">Promo & Diskon</h3>
            <p class="text-yellow-800 dark:text-yellow-200 mb-4">Promo spesial & diskon menarik setiap bulan untuk pelanggan pria modern. Dapatkan penawaran emas terbaik!</p>
            <a href="#promo" class="text-yellow-700 font-semibold hover:underline hover:text-yellow-500 transition">Lihat Promo</a>
        </div>
        <div class="glass p-8 flex flex-col items-center text-center border border-yellow-200 dark:border-yellow-700 shadow-lg hover:shadow-yellow-400/60 hover:scale-105 transition">
            <span class="mb-3">
                <!-- SVG testimoni gold -->
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#facc15" opacity=".15"/><path fill="#f59e42" d="M13 16h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
            </span>
            <h3 class="font-bold text-xl mb-2 text-yellow-700 dark:text-yellow-300">Testimoni Pelanggan</h3>
            <p class="text-yellow-800 dark:text-yellow-200 mb-4">Baca pengalaman pelanggan pria yang puas berbelanja di SIP Perhiasan. Bukti kualitas & kepercayaan!</p>
            <a href="#testimoni" class="text-yellow-700 font-semibold hover:underline hover:text-yellow-500 transition">Lihat Testimoni</a>
        </div>
        <div class="glass p-8 flex flex-col items-center text-center border border-yellow-200 dark:border-yellow-700 shadow-lg hover:shadow-yellow-400/60 hover:scale-105 transition">
            <span class="mb-3">
                <!-- SVG layanan gold -->
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#facc15" opacity=".15"/><path fill="#f59e42" d="M13 16h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
            </span>
            <h3 class="font-bold text-xl mb-2 text-yellow-700 dark:text-yellow-300">Layanan Pelanggan</h3>
            <p class="text-yellow-800 dark:text-yellow-200 mb-4">Butuh bantuan? Tim kami siap membantu Anda dengan ramah dan profesional setiap hari.</p>
            <a href="#layanan" class="text-yellow-700 font-semibold hover:underline hover:text-yellow-500 transition">Hubungi Kami</a>
        </div>
    </section>

    <!-- Produk Unggulan -->
    <section id="produk" class="max-w-7xl mx-auto mt-20 px-4">
        <h2 class="text-3xl font-extrabold text-center mb-10 text-yellow-700 dark:text-yellow-300 drop-shadow">Koleksi Produk Unggulan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach(\App\Models\Produk::orderByDesc('created_at')->limit(8)->get() as $produk)
            <div class="glass p-6 flex flex-col items-center border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 hover:scale-105 transition">
                <svg width="60" height="60" fill="none" viewBox="0 0 24 24" class="mb-2"><rect width="24" height="24" rx="12" fill="#facc15" opacity=".15"/><path d="M12 7l5 5-5 5-5-5 5-5z" fill="#f59e42"/></svg>
                <img src="{{ $produk->foto_produk ? asset('storage/'.$produk->foto_produk) : 'https://placehold.co/220x220?text=Perhiasan' }}" alt="{{ $produk->nama_produk }}" class="w-36 h-36 object-cover rounded-xl mb-4 border shadow">
                <h3 class="font-bold text-lg text-center mb-1 text-yellow-800 dark:text-yellow-200">{{ $produk->nama_produk }}</h3>
                <p class="text-yellow-700 dark:text-yellow-300 font-bold mb-2 text-lg">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <a href="{{ route('produk.index') }}" class="px-5 py-2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded-full shadow hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition">Lihat Detail</a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('produk.index') }}" class="inline-block px-10 py-3 bg-gradient-to-r from-yellow-100 to-yellow-300 text-yellow-800 rounded-full font-bold hover:bg-yellow-200 transition">Lihat Semua Produk</a>
        </div>
    </section>

    <!-- Promo Section -->
    <section id="promo" class="max-w-5xl mx-auto mt-24 px-4">
        <h2 class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mb-6">Promo Spesial Bulan Ini</h2>
        <div class="glass p-8 flex flex-col md:flex-row items-center justify-between gap-8 border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 transition">
            <div class="flex-1">
                <h3 class="font-bold text-xl mb-2 text-yellow-700 dark:text-yellow-200">Diskon hingga 30% untuk koleksi pria terbaru!</h3>
                <p class="text-yellow-800 dark:text-yellow-200 mb-4">Jangan lewatkan kesempatan untuk mendapatkan perhiasan maskulin dengan harga spesial dan bonus menarik setiap pembelian emas!</p>
                <a href="{{ route('produk.index') }}" class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded-full shadow hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition font-semibold">Belanja Sekarang</a>
            </div>
            <svg width="180" height="120" viewBox="0 0 180 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="rounded-xl shadow border">
                <rect width="180" height="120" rx="20" fill="#fef9c3"/>
                <ellipse cx="90" cy="80" rx="60" ry="30" fill="#fde68a" opacity=".15"/>
                <rect x="50" y="40" width="80" height="40" rx="12" fill="#fde68a"/>
                <rect x="70" y="60" width="40" height="10" rx="5" fill="#facc15"/>
            </svg>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section id="testimoni" class="max-w-5xl mx-auto mt-24 px-4">
        <h2 class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mb-6">Apa Kata Pelanggan Pria?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass p-8 border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 transition">
                <div class="flex items-center gap-3 mb-3">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#facc15"/><path d="M12 7a3 3 0 110 6 3 3 0 010-6zm0 8c-2.67 0-8 1.34-8 4v1h16v-1c0-2.66-5.33-4-8-4z" fill="#fde68a"/></svg>
                    <span class="font-semibold text-base text-yellow-700 dark:text-yellow-200">Rizal, Surabaya</span>
                </div>
                <p class="italic text-yellow-800 dark:text-yellow-200 mb-2 text-lg">"Desain maskulin dan pelayanan sangat ramah. Saya sangat puas membeli gelang di sini!"</p>
            </div>
            <div class="glass p-8 border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 transition">
                <div class="flex items-center gap-3 mb-3">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#fde68a"/><path d="M12 7a3 3 0 110 6 3 3 0 010-6zm0 8c-2.67 0-8 1.34-8 4v1h16v-1c0-2.66-5.33-4-8-4z" fill="#facc15"/></svg>
                    <span class="font-semibold text-base text-yellow-700 dark:text-yellow-200">Bambang, Bandung</span>
                </div>
                <p class="italic text-yellow-800 dark:text-yellow-200 mb-2 text-lg">"Banyak promo menarik dan pengiriman cepat. Sangat direkomendasikan untuk pria!"</p>
            </div>
        </div>
    </section>

    <!-- Layanan Pelanggan Section -->
    <section id="layanan" class="max-w-5xl mx-auto mt-24 px-4 mb-20">
        <h2 class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mb-6">Layanan Pelanggan</h2>
        <div class="glass p-8 flex flex-col md:flex-row items-center gap-8 border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 transition">
            <div class="flex-1">
                <p class="mb-4 text-yellow-800 dark:text-yellow-200 text-lg">Ada pertanyaan atau butuh bantuan? Hubungi tim kami melalui WhatsApp atau email, kami siap membantu Anda setiap hari!</p>
                <a href="mailto:cs@sipperhiasan.com" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded-full shadow hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition font-semibold">Email Kami</a>
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-200 to-yellow-400 text-yellow-900 rounded-full shadow hover:scale-105 hover:from-yellow-300 hover:to-yellow-500 transition font-semibold ml-3">WhatsApp</a>
            </div>
            <svg width="140" height="140" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg" class="rounded-full border shadow">
                <circle cx="70" cy="70" r="70" fill="#fef9c3"/>
                <ellipse cx="70" cy="90" rx="40" ry="20" fill="#fde68a" opacity=".15"/>
                <ellipse cx="70" cy="70" rx="30" ry="35" fill="#fef08a"/>
                <circle cx="70" cy="65" r="22" fill="#facc15"/>
                <ellipse cx="70" cy="80" rx="16" ry="10" fill="#f59e42"/>
                <ellipse cx="70" cy="60" rx="8" ry="10" fill="#fff"/>
                <ellipse cx="66" cy="63" rx="2" ry="3" fill="#facc15"/>
                <ellipse cx="74" cy="63" rx="2" ry="3" fill="#facc15"/>
                <rect x="64" y="70" width="12" height="4" rx="2" fill="#fff"/>
                <rect x="67" y="71" width="6" height="2" rx="1" fill="#facc15"/>
            </svg>
        </div>
    </section>

    <!-- Footer -->
    <footer class="w-full bg-gradient-to-r from-yellow-50 via-yellow-100 to-yellow-200 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 border-t border-yellow-100 dark:border-yellow-900 mt-24 py-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6 gap-4">
            <div class="flex items-center gap-2">
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" fill="#fef9c3" stroke="#facc15" stroke-width="2"/><ellipse cx="24" cy="30" rx="14" ry="8" fill="#fde68a" opacity=".3"/><ellipse cx="24" cy="22" rx="10" ry="12" fill="#fef08a"/><circle cx="24" cy="20" r="8" fill="#facc15"/></svg>
                <span class="font-bold text-yellow-700 dark:text-yellow-300">SIP Perhiasan</span>
            </div>
            <div class="text-yellow-700 dark:text-yellow-200 text-sm">&copy; {{ date('Y') }} SIP Perhiasan. All rights reserved.</div>
            <div class="flex gap-3">
                <a href="mailto:cs@sipperhiasan.com" class="hover:text-yellow-600 text-yellow-700 dark:text-yellow-200 transition" title="Email"><svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect width="24" height="24" rx="12" fill="#fef9c3"/><path d="M4 8l8 6 8-6" stroke="#facc15" stroke-width="2"/><rect x="4" y="8" width="16" height="8" rx="2" stroke="#facc15" stroke-width="2"/></svg></a>
                <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-yellow-600 text-yellow-700 dark:text-yellow-200 transition" title="WhatsApp"><svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect width="24" height="24" rx="12" fill="#fef9c3"/><path d="M7 17c2.5 1.5 7.5 1.5 10 0M12 2a10 10 0 100 20 10 10 0 000-20z" stroke="#facc15" stroke-width="2"/></svg></a>
                <a href="#" class="hover:text-yellow-600 text-yellow-700 dark:text-yellow-200 transition" title="Instagram"><svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect width="24" height="24" rx="12" fill="#fef9c3"/><rect x="7" y="7" width="10" height="10" rx="5" stroke="#facc15" stroke-width="2"/><circle cx="17" cy="7" r="1" fill="#facc15"/></svg></a>
            </div>
        </div>
    </footer>
</body>
</html>
