
{{-- Product data now comes from the route controller --}}

@extends('layouts.app')

@section('title', 'Bliss Silversmith - Sistem Informasi Penjualan Perhiasan')

@section('content')

    <!-- Hero Section: Modern Silver & Gold Theme -->
    <section class="relative py-58 md:py-60 bg-gradient-to-br from-slate-50 via-gray-100 to-slate-200 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <div class="absolute -inset-x-40 -top-20 h-60 bg-gradient-to-b from-slate-100 to-transparent dark:from-gray-700 rounded-full blur-3xl"></div>
            <div class="absolute -inset-x-40 -bottom-20 h-60 bg-gradient-to-t from-indigo-100 to-transparent dark:from-indigo-900 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
            <div class="flex-1 flex justify-center md:justify-start mb-4 md:mb-0">
                <div class="backdrop-blur bg-white/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 shadow-xl rounded-3xl p-6 max-w-xl w-full text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1 bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-300 rounded-full mb-3">
                        <i class="fa-solid fa-gem"></i> <span class="text-sm font-semibold">Premium Jewelry Collection</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-3 tracking-tight leading-tight text-slate-800 dark:text-slate-100" style="letter-spacing:0.5px;">
                        <span class="block">Koleksi <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-slate-500 dark:from-indigo-400 dark:to-slate-300">Silver & Gold</span></span>
                        <span class="block text-xl md:text-2xl lg:text-3xl font-medium text-slate-500 dark:text-slate-400">UMKM Bliss Silversmith</span>
                    </h1>
                    <p class="text-base md:text-lg mb-4 text-slate-600 dark:text-slate-300 leading-relaxed">
                        Temukan koleksi perhiasan silver dan emas eksklusif dengan desain modern dan elegan. Pilihan terbaik untuk hadiah, investasi, dan penyempurna gaya.
                    </p>
                    <div class="flex flex-col md:flex-row gap-3 md:items-center justify-center md:justify-start">
                        <a href="{{ route('produk.landing') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-bold rounded-full shadow-lg transition">
                            <i class="fa-solid fa-gem"></i> Lihat Koleksi
                        </a>
                        <a href="#about" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-transparent border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium rounded-full transition">
                            <i class="fa-solid fa-info-circle"></i> Tentang Kami
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex justify-center items-center relative">
                <div class="grid grid-cols-2 gap-3 max-w-md">
                    <div class="space-y-3">
                        <img src="https://images.unsplash.com/photo-1603561591411-07134e71a2a9?auto=format&fit=crop&w=400&q=80" alt="Silver Jewelry" class="rounded-2xl shadow-xl border-2 border-white dark:border-slate-700 h-44 w-full object-cover transform hover:scale-105 transition duration-500">
                        <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=400&q=80" alt="Gold Jewelry" class="rounded-2xl shadow-xl border-2 border-white dark:border-slate-700 h-28 w-full object-cover transform hover:scale-105 transition duration-500">
                    </div>
                    <div class="space-y-3 pt-4">
                        <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=400&q=80" alt="Gold Ring" class="rounded-2xl shadow-xl border-2 border-white dark:border-slate-700 h-28 w-full object-cover transform hover:scale-105 transition duration-500">
                        <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=400&q=80" alt="Silver Necklace" class="rounded-2xl shadow-xl border-2 border-white dark:border-slate-700 h-44 w-full object-cover transform hover:scale-105 transition duration-500">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Produk Section -->
    <section id="produk" class="w-full py-16 bg-white dark:bg-slate-900">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-700 dark:text-slate-200 text-center"><i class="fa-solid fa-gem text-indigo-400 dark:text-indigo-300"></i> Koleksi Perhiasan</h2>
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
            </div>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">Pilih dan pesan perhiasan impian Anda, langsung dari pengrajin terbaik dengan desain eksklusif.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($produkList as $produk)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700 group">
                    <div class="relative overflow-hidden">
                        <img src="{{ $produk['foto'] }}" alt="{{ $produk['nama_produk'] }}" class="w-full h-48 object-cover bg-slate-100 dark:bg-slate-700 group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 text-xs bg-indigo-500/90 dark:bg-indigo-600/90 text-white px-3 py-1 rounded-full font-medium shadow-lg backdrop-blur-sm">
                            {{ $produk['kategori'] }}
                        </span>
                    </div>
                    <div class="p-5 flex flex-col gap-2">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $produk['nama_produk'] }}</h3>
                        <div class="flex justify-between items-center">
                            <p class="text-indigo-600 dark:text-indigo-300 font-semibold text-xl">Rp {{ number_format($produk['harga'],0,',','.') }}</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded">Stok: {{ $produk['stok'] }}</p>
                        </div>
                        <a href="{{ url('/produk/'.$produk['id']) }}" class="mt-2 px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('produk.landing') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-300 font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-right"></i> Lihat Semua Perhiasan
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="w-full py-16 bg-slate-50 dark:bg-slate-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-700 dark:text-slate-200 text-center"><i class="fa-solid fa-shop text-indigo-400 dark:text-indigo-300"></i> Tentang Kami</h2>
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
            </div>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">Keunggulan dan keahlian kami dalam menghasilkan perhiasan berkualitas tinggi.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="rounded-xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=800&q=80" alt="About Us" class="w-full h-auto rounded-lg shadow-lg">
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-200 mb-4">Silversmith Premium</h3>
                    <p class="text-slate-600 dark:text-slate-300 mb-4">
                        Kami adalah pengrajin perhiasan silver dan emas dengan pengalaman lebih dari 20 tahun dalam industri. Setiap perhiasan dibuat dengan ketelitian tinggi dan bahan berkualitas premium.
                    </p>
                    <p class="text-slate-600 dark:text-slate-300 mb-4">
                        Komitmen kami adalah memberikan perhiasan berkualitas tinggi dengan design elegan dan modern yang dapat menjadi bagian dari momen spesial Anda.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <a href="#contact" class="px-5 py-2 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition-colors">Hubungi Kami</a>
                        <a href="#" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg shadow transition-colors">Portofolio</a>
                    </div>
                </div>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
                <div class="bg-white dark:bg-slate-700 rounded-xl p-6 shadow-lg text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 mb-4">
                        <i class="fa-solid fa-medal text-xl text-indigo-500 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-2">Kualitas Premium</h3>
                    <p class="text-slate-600 dark:text-slate-300">Perhiasan dengan material terbaik dan pengerjaan yang teliti untuk hasil terbaik.</p>
                </div>
                
                <div class="bg-white dark:bg-slate-700 rounded-xl p-6 shadow-lg text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 mb-4">
                        <i class="fa-solid fa-gem text-xl text-indigo-500 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-2">Desain Eksklusif</h3>
                    <p class="text-slate-600 dark:text-slate-300">Setiap perhiasan memiliki desain unik dan modern yang mengikuti tren terkini.</p>
                </div>
                
                <div class="bg-white dark:bg-slate-700 rounded-xl p-6 shadow-lg text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 mb-4">
                        <i class="fa-solid fa-certificate text-xl text-indigo-500 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-2">Bergaransi</h3>
                    <p class="text-slate-600 dark:text-slate-300">Semua produk kami disertai garansi keaslian dan perawatan untuk ketenangan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="w-full py-16 bg-gradient-to-br from-slate-50 to-indigo-50 dark:from-slate-900 dark:to-slate-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-700 dark:text-slate-200 text-center"><i class="fa-solid fa-envelope text-indigo-400 dark:text-indigo-300"></i> Hubungi Kami</h2>
                <span class="h-0.5 w-12 bg-indigo-300 dark:bg-indigo-600 rounded"></span>
            </div>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">Ada pertanyaan atau ingin konsultasi tentang perhiasan? Kami siap membantu Anda.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <form class="space-y-4 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama</label>
                            <input type="text" id="name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 focus:border-transparent dark:bg-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 focus:border-transparent dark:bg-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Subjek</label>
                            <select id="subject" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 focus:border-transparent dark:bg-slate-700 dark:text-white">
                                <option>Pertanyaan Produk</option>
                                <option>Custom Order</option>
                                <option>Informasi Harga</option>
                                <option>Garansi & Perawatan</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pesan</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-600 focus:border-transparent dark:bg-slate-700 dark:text-white"></textarea>
                        </div>
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
                <div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg mb-6">
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fa-solid fa-location-dot text-indigo-500 dark:text-indigo-300"></i>
                                </div>
                                <div class="ml-3 mt-1">
                                    <p class="text-slate-600 dark:text-slate-300">Jl. Perhiasan Indah No. 123, Kota Perak</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fa-solid fa-phone text-indigo-500 dark:text-indigo-300"></i>
                                </div>
                                <div class="ml-3 mt-1">
                                    <p class="text-slate-600 dark:text-slate-300">+62 123-4567-8900</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fa-solid fa-envelope text-indigo-500 dark:text-indigo-300"></i>
                                </div>
                                <div class="ml-3 mt-1">
                                    <p class="text-slate-600 dark:text-slate-300">info@silversmith.id</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-4">Jam Operasional</h3>
                        <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                            <li class="flex justify-between"><span>Senin - Jumat:</span> <span>09:00 - 20:00</span></li>
                            <li class="flex justify-between"><span>Sabtu:</span> <span>10:00 - 18:00</span></li>
                            <li class="flex justify-between"><span>Minggu:</span> <span>10:00 - 15:00</span></li>
                        </ul>
                        
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-4 mt-6">Ikuti Kami</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-500 dark:text-slate-300 dark:hover:bg-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-500 dark:text-slate-300 dark:hover:bg-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-500 dark:text-slate-300 dark:hover:bg-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-500 dark:text-slate-300 dark:hover:bg-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Cart logic (localStorage)
        const produkList = @json($produkList);
        let cart = JSON.parse(localStorage.getItem('cart') || '{}');

        function updateCartCount() {
            let count = 0;
            for (const id in cart) count += cart[id].qty;
            const cartCount = document.getElementById('cartCount');
            if(cartCount) cartCount.innerText = count;
        }

        function addToCart(id) {
            const produk = produkList.find(p => p.id === id);
            if (!produk) return;
            if (!cart[id]) cart[id] = { ...produk, qty: 0 };
            if (cart[id].qty < produk.stok) cart[id].qty++;
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            window.location.href = "{{ route('cart') }}";
        }

        // Redirect to cart page when cart button is clicked
        const cartBtn = document.getElementById('cartBtn');
        if(cartBtn) cartBtn.onclick = function() {
            window.location.href = "{{ route('cart') }}";
        };

        // Inisialisasi cart count saat load
        updateCartCount();
    </script>
    
    <script>
    // Dark mode toggle
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
    }
    </script>
@endsection