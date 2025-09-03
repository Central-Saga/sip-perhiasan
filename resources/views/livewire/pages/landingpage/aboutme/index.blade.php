<?php
use function Livewire\Volt\{ layout };

layout('components.layouts.landing');
?>

<div>
    <!-- Hero Section -->
    <section
        class="relative min-h-[70vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="about-bg-1 absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="about-bg-2 absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl">
            </div>
            <div
                class="about-bg-3 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl">
            </div>
        </div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="about-particle absolute w-2 h-2 bg-white/30 rounded-full"
                style="top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="about-particle absolute w-1 h-1 bg-purple-300/40 rounded-full"
                style="top: 60%; left: 20%; animation-delay: 1s;"></div>
            <div class="about-particle absolute w-3 h-3 bg-indigo-300/30 rounded-full"
                style="top: 30%; right: 15%; animation-delay: 2s;"></div>
            <div class="about-particle absolute w-2 h-2 bg-pink-300/40 rounded-full"
                style="bottom: 30%; left: 30%; animation-delay: 3s;"></div>
            <div class="about-particle absolute w-1 h-1 bg-white/20 rounded-full"
                style="bottom: 20%; right: 25%; animation-delay: 4s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left space-y-8">
                    <!-- Badge -->
                    <div
                        class="about-hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                        <i class="fa-solid fa-gem text-purple-300"></i>
                        <span>Tentang Bliss Silversmith</span>
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-4">
                        <h1
                            class="about-hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                            <span class="block">Merayakan</span>
                            <span
                                class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                                Keindahan
                            </span>
                            <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                                Perak Premium
                            </span>
                        </h1>
                    </div>

                    <!-- Description -->
                    <p
                        class="about-hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Bliss Silversmith hadir untuk menghadirkan perhiasan perak yang elegan, fungsional, dan
                        bermakna—didesain untuk menyempurnakan momen istimewa sekaligus menemani keseharian Anda.
                    </p>

                    <!-- Stats -->
                    <div class="about-hero-stats grid grid-cols-3 gap-6 pt-8 border-t border-white/10">
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white">20+</div>
                            <div class="text-sm text-white/60">Tahun Pengalaman</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white">5000+</div>
                            <div class="text-sm text-white/60">Produk Terjual</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white">100%</div>
                            <div class="text-sm text-white/60">Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Image -->
                <div class="relative flex items-center justify-center">
                    <div class="about-showcase relative">
                        <!-- Main Image -->
                        <div
                            class="about-image-card relative z-10 transform rotate-3 hover:rotate-0 transition-transform duration-500 group">
                            <!-- Glow Effect -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10">
                            </div>
                            <div
                                class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-2xl hover:shadow-purple-500/25 transition-shadow duration-500 max-w-sm">
                                <div
                                    class="aspect-square bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=800&q=80"
                                        alt="Bliss Silversmith Workshop"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                                <h3 class="text-lg font-bold text-white mb-2">Workshop Premium</h3>
                                <p class="text-white/70 mb-3 text-sm">Dikerjakan dengan hati dan ketelitian tinggi</p>
                                <div class="text-xl font-bold text-purple-300">Kualitas Terbaik</div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div
                            class="about-floating-1 absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-500 rounded-xl shadow-xl transform rotate-12 hover:rotate-0 transition-transform duration-300">
                        </div>
                        <div
                            class="about-floating-2 absolute -bottom-4 -left-4 w-18 h-18 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-lg shadow-xl transform -rotate-12 hover:rotate-0 transition-transform duration-300">
                        </div>
                        <div
                            class="about-floating-3 absolute top-1/2 -left-8 w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-md shadow-lg transform rotate-6 hover:rotate-0 transition-transform duration-300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6">

            <!-- Our Story Section -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                    <i class="fa-solid fa-heart"></i>
                    <span>Our Story</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Kisah &</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Filosofi
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Kami percaya bahwa perhiasan terbaik adalah yang memadukan desain apik, kenyamanan, dan makna
                    personal.
                </p>
            </div>

            <!-- Story Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
                <div class="about-story-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-lightbulb text-2xl text-purple-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Filosofi Kami</h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">Di Bliss Silversmith, setiap
                            koleksi dirancang dengan perhatian pada detail—mulai dari sketsa awal, pemilihan material,
                            hingga proses finishing—agar menghasilkan karya yang tahan lama serta menyenangkan dipakai.
                        </p>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Nama "Bliss" merepresentasikan
                            rasa bahagia yang ingin kami hadirkan saat Anda menemukan atau menghadiahkan perhiasan yang
                            tepat.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-mission-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-target text-2xl text-indigo-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Misi Kami</h3>
                        <ul class="space-y-3 text-slate-600 dark:text-slate-300">
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Menyajikan perhiasan silver premium yang nyaman dipakai sehari‑hari.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Mengutamakan detail pengerjaan, ketepatan ukuran, dan ketahanan finishing.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Mendukung personalisasi desain agar tiap karya merefleksikan kepribadian
                                    pemiliknya.</span>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                    <i class="fa-solid fa-star"></i>
                    <span>Our Values</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Nilai yang</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Kami Junjung
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Komitmen kami dalam memberikan yang terbaik untuk setiap pelanggan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="about-value-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-medal text-2xl text-purple-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Kualitas</h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Material pilihan dan kontrol
                            kualitas ketat di tiap tahap produksi untuk hasil terbaik.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-value-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-lightbulb text-2xl text-indigo-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Desain</h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Estetika modern yang tetap nyaman
                            dipakai—dengan proporsi yang seimbang dan elegan.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-value-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-hands-holding text-2xl text-green-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Servis</h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Dukungan purna jual: penyesuaian
                            ukuran, pemolesan, hingga perawatan berkala.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- Craft Process Section -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                    <i class="fa-solid fa-cogs"></i>
                    <span>Our Process</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Proses</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Perajin Kami
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Setiap perhiasan dibuat dengan proses yang teliti dan penuh perhatian pada detail.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
                <div class="about-process-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-pen-ruler text-2xl text-purple-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Desain & Sketsa</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Menjaga proporsi, ergonomi, dan karakter
                            visual yang sempurna.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-process-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-cubes text-2xl text-indigo-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Pembuatan Komponen</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Pemotongan, pembentukan, dan penyolderan
                            dengan presisi tinggi.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-process-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-screwdriver-wrench text-2xl text-green-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Finishing</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Pemolesan bertahap untuk kilau halus dan
                            tahan lama.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-process-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-yellow-500/20 to-orange-500/20 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-shield-heart text-2xl text-yellow-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Quality Check</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Pemeriksaan detail sebelum dikemas rapi
                            untuk Anda.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- Materials & Care Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
                <div class="about-material-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-gem text-2xl text-purple-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Material Premium</h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Kami mengutamakan perak
                            berkualitas dengan standar yang nyaman dipakai dan mudah dirawat. Untuk aksen, kami memilih
                            batu zirkonia/kristal berkualitas atau batu natural sesuai ketersediaan dan desain.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <div class="about-care-card group relative">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-heart text-2xl text-green-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Perawatan Mudah</h3>
                        <ul class="space-y-3 text-slate-600 dark:text-slate-300">
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Simpan di pouch/kotak kering, hindari lembap berlebih.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Bersihkan ringan dengan kain lap khusus perak setelah digunakan.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <span>Hindari paparan bahan kimia keras, parfum berlebih, dan air laut.</span>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <div
                    class="bg-gradient-to-r from-indigo-500/10 to-purple-500/10 dark:from-indigo-500/20 dark:to-purple-500/20 border border-indigo-200 dark:border-indigo-800 rounded-2xl p-12 shadow-xl">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 mb-6">
                        <i class="fa-solid fa-wand-magic-sparkles text-3xl text-indigo-300"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-800 dark:text-slate-100 mb-4">Punya Ide Desain Impian?</h3>
                    <p class="text-lg text-slate-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto">Kami menerima custom
                        request—dari sketsa sederhana hingga personalisasi detail untuk mewujudkan perhiasan impian
                        Anda.</p>
                    <a href="{{ route('custom') }}"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Buat Custom Request</span>
                    </a>
                </div>
            </div>
        </div>
