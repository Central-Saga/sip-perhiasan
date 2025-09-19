<?php

use function Livewire\Volt\{ layout, state, mount };
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

// State and data loading
state([
    'produkList' => [],
]);

mount(function () {
    $this->produkList = Produk::where('status', true)->get();
});

?>

<div>
    <div>
        <!-- Hero Section: Modern Design -->
        <section
            class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse">
                </div>
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl animate-pulse"
                    style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl animate-pulse"
                    style="animation-delay: 2s;"></div>
            </div>

            <!-- Floating Particles -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="particle absolute w-2 h-2 bg-white/30 rounded-full"
                    style="top: 20%; left: 10%; animation-delay: 0s;"></div>
                <div class="particle absolute w-1 h-1 bg-purple-300/40 rounded-full"
                    style="top: 60%; left: 20%; animation-delay: 1s;"></div>
                <div class="particle absolute w-3 h-3 bg-indigo-300/30 rounded-full"
                    style="top: 30%; right: 15%; animation-delay: 2s;"></div>
                <div class="particle absolute w-2 h-2 bg-pink-300/40 rounded-full"
                    style="bottom: 30%; left: 30%; animation-delay: 3s;"></div>
                <div class="particle absolute w-1 h-1 bg-white/20 rounded-full"
                    style="bottom: 20%; right: 25%; animation-delay: 4s;"></div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left space-y-8">
                        <!-- Badge -->
                        <div
                            class="hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                            <i class="fa-solid fa-gem text-purple-300"></i>
                            <span>Premium Silver Collection</span>
                        </div>

                        <!-- Main Heading -->
                        <div class="space-y-4">
                            <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-black text-white leading-tight">
                                <span class="block">Koleksi</span>
                                <span
                                    class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                                    Silver
                                </span>
                                <span class="block text-2xl md:text-3xl lg:text-4xl font-light text-white/80 mt-2">
                                    Bliss Silversmith
                                </span>
                            </h1>
                        </div>

                        <!-- Description -->
                        <p
                            class="hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-lg mx-auto lg:mx-0">
                            Temukan koleksi perhiasan silver eksklusif dengan desain modern dan elegan.
                            Pilihan terbaik untuk hadiah dan penyempurna gaya Anda.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="hero-buttons flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="#produk"
                                class="group relative px-8 py-4 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-gem"></i>
                                    Lihat Koleksi
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-purple-400 to-indigo-400 rounded-full blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                                </div>
                            </a>
                            <a href="#about"
                                class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white font-medium rounded-full transition-all duration-300 transform hover:scale-105">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-info-circle"></i>
                                    Tentang Kami
                                </span>
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="hero-stats grid grid-cols-3 gap-6 pt-8 border-t border-white/10">
                            <div class="text-center">
                                <div class="text-2xl md:text-3xl font-bold text-white">500+</div>
                                <div class="text-sm text-white/60">Produk</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl md:text-3xl font-bold text-white">1000+</div>
                                <div class="text-sm text-white/60">Pelanggan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl md:text-3xl font-bold text-white">5★</div>
                                <div class="text-sm text-white/60">Rating</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - 3D Card Showcase -->
                    <div class="relative flex items-center justify-center">
                        <div class="hero-showcase relative">
                            <!-- Main Product Card -->
                            <div
                                class="product-card relative z-10 transform rotate-6 hover:rotate-0 transition-transform duration-500 group">
                                <!-- Glow Effect -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10">
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl hover:shadow-purple-500/25 transition-shadow duration-500 max-w-sm">
                                    <div
                                        class="aspect-square bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                                        <img src="{{ asset('assets/img/ring.png') }}" alt="Silver Ring Premium"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                    </div>
                                    <h3 class="text-lg font-bold text-white mb-2">Silver Ring Premium</h3>
                                    <p class="text-white/70 mb-3 text-sm">Desain eksklusif dengan kualitas terbaik</p>
                                </div>
                            </div>

                            <!-- Floating Cards -->
                            <div
                                class="floating-card-1 absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-500 rounded-xl shadow-xl transform rotate-12 hover:rotate-0 transition-transform duration-300">
                            </div>
                            <div
                                class="floating-card-2 absolute -bottom-4 -left-4 w-18 h-18 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-lg shadow-xl transform -rotate-12 hover:rotate-0 transition-transform duration-300">
                            </div>
                            <div
                                class="floating-card-3 absolute top-1/2 -left-8 w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-md shadow-lg transform rotate-6 hover:rotate-0 transition-transform duration-300">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div
                class="scroll-down-arrow absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 animate-bounce cursor-pointer hover:text-white/80 transition-colors duration-300">
                <i class="fa-solid fa-chevron-down text-2xl"></i>
            </div>
        </section>

        <!-- Produk Section -->
        <section id="produk"
            class="relative w-full py-20 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                        <i class="fa-solid fa-gem"></i>
                        <span>Premium Collection</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                        <span class="block">Koleksi</span>
                        <span
                            class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                            Terbaru
                        </span>
                    </h2>

                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                        Temukan {{ min(10, count($this->produkList)) }} perhiasan silver terbaru kami dengan desain
                        eksklusif dan kualitas premium yang memukau.
                    </p>
                </div>

                <!-- Products Carousel -->
                <div class="relative">
                    <!-- Carousel Container -->
                    <div class="carousel-container overflow-hidden">
                        <div class="carousel-track flex gap-6 transition-transform duration-500 ease-out">
                            @foreach($this->produkList->take(10) as $index => $produk)
                            <div class="product-item carousel-slide flex-shrink-0 w-80 group relative">
                                <!-- Product Card -->
                                <div
                                    class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                    <!-- Image Container -->
                                    <div class="relative overflow-hidden">
                                        <div
                                            class="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                                            @if($produk->foto)
                                            <img src="{{ Storage::url($produk->foto) }}"
                                                alt="{{ $produk->nama_produk ?? '' }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            @else
                                            <i class="fa-solid fa-gem text-6xl text-slate-400 dark:text-slate-500"></i>
                                            @endif
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm">
                                                {{ $produk->kategori ?? 'Premium' }}
                                            </span>
                                        </div>

                                        <!-- Hover Overlay -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="absolute bottom-4 left-4 right-4">
                                                <a href="{{ url('/produk/'.$produk->id) }}"
                                                    class="w-full bg-white/90 backdrop-blur-md text-slate-800 font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 hover:bg-white transition-colors duration-300">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span>Lihat Detail</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-6">
                                        <h3
                                            class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">
                                            {{ $produk->nama_produk ?? 'Silver Premium' }}
                                        </h3>

                                        <div class="flex justify-between items-center mb-4">
                                            <div
                                                class="text-2xl font-black text-transparent bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text">
                                                Rp {{ number_format($produk->harga ?? 0,0,',','.') }}
                                            </div>
                                            <div
                                                class="flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400">
                                                <i class="fa-solid fa-box"></i>
                                                <span>{{ $produk->stok ?? 0 }} tersedia</span>
                                            </div>
                                        </div>

                                        <!-- Features -->
                                        <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-300">
                                            <div class="flex items-center gap-1">
                                                <i class="fa-solid fa-medal text-indigo-500"></i>
                                                <span>Premium</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <i class="fa-solid fa-shipping-fast text-green-500"></i>
                                                <span>Gratis Ongkir</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Elements -->
                                <div
                                    class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                <div
                                    class="absolute -bottom-2 -left-2 w-3 h-3 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <button
                        class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 z-10">
                        <i class="fa-solid fa-chevron-left text-lg"></i>
                    </button>

                    <button
                        class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 z-10">
                        <i class="fa-solid fa-chevron-right text-lg"></i>
                    </button>

                    <!-- Dots Indicator -->
                    <div class="flex justify-center gap-2 mt-8">
                        @for($i = 0; $i < ceil(min(10, count($this->produkList)) / 3); $i++)
                            <button
                                class="carousel-dot w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 hover:bg-indigo-500 dark:hover:bg-indigo-400 transition-colors duration-300 {{ $i === 0 ? 'bg-indigo-500 dark:bg-indigo-400' : '' }}"
                                data-slide="{{ $i }}"></button>
                            @endfor
                    </div>
                </div>

                <!-- View All Button -->
                <div class="text-center mt-16">
                    <a href="{{ route('produk') }}"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <i class="fa-solid fa-gem"></i>
                        <span>Lihat Semua Koleksi</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about"
            class="relative w-full py-20 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="about-bg-1 absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl">
                </div>
                <div class="about-bg-2 absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl">
                </div>
                <div
                    class="about-bg-3 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/5 rounded-full blur-2xl">
                </div>
            </div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium mb-6">
                        <i class="fa-solid fa-shop text-purple-300"></i>
                        <span>Our Story</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6">
                        <span class="block">Tentang</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Kami
                        </span>
                    </h2>

                    <p class="text-lg md:text-xl text-white/70 max-w-3xl mx-auto leading-relaxed">
                        Keunggulan dan keahlian kami dalam menghasilkan perhiasan silver berkualitas tinggi dengan
                        desain yang memukau.
                    </p>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                    <!-- Image Section -->
                    <div class="relative">
                        <div class="relative z-10">
                            <div
                                class="aspect-square bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-slate-700 dark:to-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                                <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=800&q=80"
                                    alt="About Us"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div
                            class="about-floating-1 absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-pink-400 to-purple-500 rounded-2xl shadow-xl transform rotate-12 hover:rotate-0 transition-transform duration-300">
                        </div>
                        <div
                            class="about-floating-2 absolute -bottom-4 -left-4 w-16 h-16 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-xl shadow-xl transform -rotate-12 hover:rotate-0 transition-transform duration-300">
                        </div>
                        <div
                            class="about-floating-3 absolute top-1/2 -left-6 w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-3xl md:text-4xl font-bold text-white mb-6">
                                Bliss Silversmith
                                <span class="block text-2xl md:text-3xl font-light text-white/80">Premium</span>
                            </h3>

                            <div class="space-y-6 text-white/80 leading-relaxed">
                                <p class="text-lg">
                                    Kami adalah pengrajin perhiasan silver dengan pengalaman lebih dari <span
                                        class="text-purple-300 font-semibold">20 tahun</span> dalam industri.
                                    Setiap perhiasan dibuat dengan ketelitian tinggi dan bahan berkualitas premium.
                                </p>

                                <p class="text-lg">
                                    Komitmen kami adalah memberikan perhiasan berkualitas tinggi dengan desain elegan
                                    dan modern
                                    yang dapat menjadi bagian dari <span class="text-pink-300 font-semibold">momen
                                        spesial</span> Anda.
                                </p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 pt-6 border-t border-white/10">
                            <div class="text-center">
                                <div class="text-3xl md:text-4xl font-bold text-white">20+</div>
                                <div class="text-sm text-white/60">Tahun Pengalaman</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl md:text-4xl font-bold text-white">5000+</div>
                                <div class="text-sm text-white/60">Produk Terjual</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl md:text-4xl font-bold text-white">100%</div>
                                <div class="text-sm text-white/60">Kepuasan</div>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <a href="#contact"
                                class="group relative px-8 py-4 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-phone"></i>
                                    Hubungi Kami
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-purple-400 to-indigo-400 rounded-full blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-card group relative">
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-medal text-2xl text-purple-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-4">Kualitas Premium</h3>
                            <p class="text-white/70 leading-relaxed">Perhiasan dengan material terbaik dan pengerjaan
                                yang teliti untuk hasil terbaik yang memuaskan.</p>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                    </div>

                    <div class="feature-card group relative">
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-gem text-2xl text-indigo-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-4">Desain Eksklusif</h3>
                            <p class="text-white/70 leading-relaxed">Setiap perhiasan memiliki desain unik dan modern
                                yang mengikuti tren terkini dan selera masa kini.</p>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                    </div>

                    <div class="feature-card group relative">
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-certificate text-2xl text-green-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-4">Bergaransi</h3>
                            <p class="text-white/70 leading-relaxed">Semua produk kami disertai garansi keaslian dan
                                perawatan untuk ketenangan dan kepercayaan Anda.</p>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact"
            class="relative w-full py-20 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                        <i class="fa-solid fa-envelope"></i>
                        <span>Get In Touch</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                        <span class="block">Hubungi</span>
                        <span
                            class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                            Kami
                        </span>
                    </h2>

                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                        Ada pertanyaan atau ingin konsultasi tentang perhiasan silver? Kami siap membantu Anda dengan
                        layanan terbaik.
                    </p>
                </div>

                <!-- Contact Content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Information -->
                    <div class="space-y-8">
                        <div
                            class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl">
                            <h3
                                class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                                <i class="fa-solid fa-phone text-indigo-500"></i>
                                Informasi Kontak
                            </h3>

                            <div class="space-y-6">
                                <!-- Phone -->
                                <div class="flex items-start gap-4 group">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-phone text-indigo-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">Telepon</h4>
                                        <p class="text-slate-600 dark:text-slate-300">0813-3888-7248</p>
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="flex items-start gap-4 group">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-brands fa-whatsapp text-green-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">WhatsApp</h4>
                                        <p class="text-slate-600 dark:text-slate-300">0813-3888-7248</p>
                                        <a href="https://wa.me/6281338887248"
                                            class="text-green-500 hover:text-green-600 transition-colors duration-300 text-sm">
                                            <i class="fa-solid fa-external-link-alt mr-1"></i>
                                            Chat Sekarang
                                        </a>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-start gap-4 group">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-envelope text-blue-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">Email</h4>
                                        <p class="text-slate-600 dark:text-slate-300">blisssilversmith@gmail.com</p>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="flex items-start gap-4 group">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-purple-500/20 to-pink-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-map-marker-alt text-purple-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">Alamat</h4>
                                        <p class="text-slate-600 dark:text-slate-300">
                                            Jl. Bird Park Jl. Serma Cok Ngurah Gambir No.3<br>
                                            Batubulan, Kec. Sukawati<br>
                                            Kabupaten Gianyar, Bali 80582
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div
                            class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl">
                            <h3
                                class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                                <i class="fa-solid fa-share-alt text-indigo-500"></i>
                                Ikuti Kami
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <a href="#"
                                    class="group flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 transition-all duration-300">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-brands fa-facebook-f text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 dark:text-slate-100">Facebook</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300">@blisssilversmith</p>
                                    </div>
                                </a>

                                <a href="#"
                                    class="group flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-pink-500/10 to-pink-600/10 hover:from-pink-500/20 hover:to-pink-600/20 transition-all duration-300">
                                    <div
                                        class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-brands fa-instagram text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 dark:text-slate-100">Instagram</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300">@blisssilversmith</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours & Quick Contact -->
                    <div class="space-y-8">
                        <!-- Operating Hours -->
                        <div
                            class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl">
                            <h3
                                class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                                <i class="fa-solid fa-clock text-indigo-500"></i>
                                Jam Operasional
                            </h3>

                            <div class="grid grid-cols-1 gap-3">
                                <!-- Hari Buka -->
                                <div class="space-y-2">
                                    <h4
                                        class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-green-500"></i>
                                        Hari Buka
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Senin</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Selasa</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Rabu</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Kamis</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Jumat</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                            <span
                                                class="text-sm font-medium text-slate-700 dark:text-slate-200">Sabtu</span>
                                            <span
                                                class="text-sm text-green-600 dark:text-green-400 font-semibold">10:00-18:00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hari Tutup -->
                                <div class="space-y-2">
                                    <h4
                                        class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-times-circle text-red-500"></i>
                                        Hari Tutup
                                    </h4>
                                    <div
                                        class="flex justify-between items-center p-3 rounded-lg bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800">
                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-200">Minggu</span>
                                        <span class="text-sm text-red-600 dark:text-red-400 font-semibold">Tutup</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-info-circle text-green-500"></i>
                                    <p class="text-green-700 dark:text-green-300 text-sm">
                                        <strong>Catatan:</strong> Kami tutup pada hari Minggu. Konsultasi online 24/7
                                        melalui WhatsApp untuk pertanyaan mendesak.
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    // Cart count initialization for home page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize cart count when page loads
        if (window.updateCartCount) {
            window.updateCartCount();
        }
    });
</script>
