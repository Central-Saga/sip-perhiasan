<?php
use function Livewire\Volt\layout;
layout('components.layouts.landing');
?>

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
            <div class="text-center space-y-8">
                <!-- Badge -->
                <div
                    class="hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                    <i class="fa-solid fa-palette text-purple-300"></i>
                    <span>Custom Design Service</span>
                </div>

                <!-- Main Heading -->
                <div class="space-y-4">
                    <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-black text-white leading-tight">
                        <span class="block">Custom</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Request
                        </span>
                        <span class="block text-2xl md:text-3xl lg:text-4xl font-light text-white/80 mt-2">
                            Perhiasan Eksklusif
                        </span>
                    </h1>
                </div>

                <!-- Description -->
                <p class="hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                    Wujudkan perhiasan impian Anda dengan desain custom yang unik dan personal.
                    Tim pengrajin berpengalaman kami siap mewujudkan visi Anda menjadi karya seni yang memukau.
                </p>

                <!-- CTA Button -->
                <div class="hero-buttons flex justify-center">
                    <a href="#custom-form"
                        class="group relative px-8 py-4 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-pencil"></i>
                            Mulai Custom Request
                        </span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-400 to-indigo-400 rounded-full blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                        </div>
                    </a>
                </div>

                <!-- Stats -->
                <div class="hero-stats grid grid-cols-3 gap-6 pt-8 border-t border-white/10 max-w-md mx-auto">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white">100%</div>
                        <div class="text-sm text-white/60">Custom</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white">20+</div>
                        <div class="text-sm text-white/60">Tahun</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white">5★</div>
                        <div class="text-sm text-white/60">Rating</div>
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
    <!-- Custom Form Section -->
    <section id="custom-form"
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
                    <i class="fa-solid fa-pencil"></i>
                    <span>Custom Request Form</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Buat</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Perhiasan
                    </span>
                    <span
                        class="block text-2xl md:text-3xl lg:text-4xl font-light text-slate-600 dark:text-slate-300 mt-2">
                        Impian Anda
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Isi form di bawah ini dengan detail permintaan Anda. Semakin spesifik, semakin baik hasilnya.
                </p>
            </div>

            <!-- Form Container -->
            <div class="max-w-4xl mx-auto">
                <div
                    class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-3xl p-8 md:p-12 shadow-2xl">
                    <!-- Glow Effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 rounded-3xl blur-xl -z-10">
                    </div>

                    @if(session('message'))
                    <div
                        class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-300 p-4 mb-8 rounded-xl">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-green-500"></i>
                            {{ session('message') }}
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('custom.submit') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf

                        <!-- First Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="kategori"
                                    class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                    <i class="fa-solid fa-gem text-indigo-500 mr-2"></i>
                                    Kategori Perhiasan
                                </label>
                                <input type="text" name="kategori" id="kategori"
                                    placeholder="Contoh: Cincin, Kalung, Gelang, Anting, dll"
                                    class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-6 py-4 bg-white/50 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                                @error('kategori')
                                <span class="text-red-500 text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label for="material"
                                    class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                    <i class="fa-solid fa-atom text-purple-500 mr-2"></i>
                                    Material
                                </label>
                                <select name="material" id="material"
                                    class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-6 py-4 bg-white/50 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                                    <option value="">Pilih Material</option>
                                    <option value="Emas Kuning">Emas Kuning</option>
                                    <option value="Emas Putih">Emas Putih</option>
                                    <option value="Perak">Perak</option>
                                    <option value="Platinum">Platinum</option>
                                    <option value="Titanium">Titanium</option>
                                    <option value="Stainless Steel">Stainless Steel</option>
                                </select>
                                @error('material')
                                <span class="text-red-500 text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Second Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="ukuran"
                                    class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                    <i class="fa-solid fa-ruler text-pink-500 mr-2"></i>
                                    Ukuran
                                </label>
                                <input type="text" name="ukuran" id="ukuran"
                                    placeholder="Contoh: Cincin ukuran 7, Gelang 18cm, dll"
                                    class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-6 py-4 bg-white/50 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                                @error('ukuran')
                                <span class="text-red-500 text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label for="berat"
                                    class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                    <i class="fa-solid fa-weight text-orange-500 mr-2"></i>
                                    Berat
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" name="berat" id="berat"
                                        placeholder="Masukkan angka, contoh: 3"
                                        class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-6 py-4 pr-20 bg-white/50 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                                    <span
                                        class="absolute inset-y-0 right-6 flex items-center text-slate-500 dark:text-slate-400 text-sm font-medium">gram</span>
                                </div>
                                @error('berat')
                                <span class="text-red-500 text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- Upload Section -->
                        <div class="space-y-3">
                            <label for="gambar_referensi"
                                class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                <i class="fa-solid fa-image text-cyan-500 mr-2"></i>
                                Upload Referensi
                            </label>
                            <div
                                class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-8 text-center hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors duration-300 bg-gradient-to-br from-slate-50/50 to-indigo-50/50 dark:from-slate-700/30 dark:to-slate-800/30">
                                <label for="file-upload" class="cursor-pointer block group">
                                    <div
                                        class="text-slate-500 dark:text-slate-400 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors duration-300">
                                        <i
                                            class="fa-solid fa-cloud-arrow-up text-4xl mb-4 group-hover:scale-110 transition-transform duration-300"></i>
                                        <p class="text-lg font-medium mb-2">Klik untuk upload gambar referensi</p>
                                        <p class="text-sm opacity-75">(opsional, maks. 2MB)</p>
                                        <p class="text-xs mt-2 opacity-60">JPG, PNG, atau GIF</p>
                                    </div>
                                    <input id="file-upload" type="file" name="gambar_referensi" class="hidden"
                                        accept="image/*">
                                </label>
                                <div id="previewWrap" class="mt-6 hidden">
                                    <div class="text-left text-sm text-slate-600 dark:text-slate-300 mb-3 font-medium">
                                        Pratinjau:</div>
                                    <div
                                        class="mx-auto w-48 h-48 rounded-xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-lg">
                                        <img id="previewImg" src="" alt="Preview" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                            @error('gambar_referensi')
                            <span class="text-red-500 text-sm flex items-center gap-1">
                                <i class="fa-solid fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-3">
                            <label for="deskripsi"
                                class="block text-slate-700 dark:text-slate-300 font-semibold text-lg">
                                <i class="fa-solid fa-align-left text-green-500 mr-2"></i>
                                Deskripsi Kebutuhan
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="6"
                                placeholder="Jelaskan perhiasan yang Anda inginkan secara detail. Misalnya: desain, motif, warna, gaya, atau inspirasi khusus yang Anda miliki..."
                                class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-6 py-4 bg-white/50 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm resize-none"></textarea>
                            @error('deskripsi')
                            <span class="text-red-500 text-sm flex items-center gap-1">
                                <i class="fa-solid fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <div class="pt-8">
                            <button type="submit"
                                class="group relative w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl flex items-center justify-center gap-3">
                                <i
                                    class="fa-solid fa-paper-plane group-hover:scale-110 transition-transform duration-300"></i>
                                <span>Kirim Custom Request</span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-2xl blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Process Section -->
    <section class="relative w-full py-20 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/5 rounded-full blur-2xl">
            </div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium mb-6">
                    <i class="fa-solid fa-cogs text-purple-300"></i>
                    <span>Our Process</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6">
                    <span class="block">Bagaimana</span>
                    <span
                        class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                        Proses
                    </span>
                    <span class="block text-2xl md:text-3xl lg:text-4xl font-light text-white/80 mt-2">
                        Custom Request Bekerja?
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-white/70 max-w-3xl mx-auto leading-relaxed">
                    Dari ide hingga perhiasan jadi, kami memastikan setiap langkah dilakukan dengan teliti dan
                    profesional.
                </p>
            </div>

            <!-- Process Steps -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="process-card group relative">
                    <div
                        class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <!-- Step Number -->
                        <div
                            class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            1
                        </div>

                        <!-- Icon -->
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-pencil text-3xl text-purple-300"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Kirim Request</h3>
                        <p class="text-white/70 leading-relaxed">Isi form dengan detail permintaan Anda. Semakin
                            spesifik, semakin baik hasilnya. Sertakan referensi gambar jika ada.</p>
                    </div>

                    <!-- Floating Elements -->
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="process-card group relative">
                    <div
                        class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <!-- Step Number -->
                        <div
                            class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            2
                        </div>

                        <!-- Icon -->
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-comments text-3xl text-indigo-300"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Konsultasi</h3>
                        <p class="text-white/70 leading-relaxed">Tim kami akan menghubungi untuk membahas detail,
                            estimasi harga dan waktu pengerjaan. Konsultasi gratis!</p>
                    </div>

                    <!-- Floating Elements -->
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="process-card group relative">
                    <div
                        class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <!-- Step Number -->
                        <div
                            class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            3
                        </div>

                        <!-- Icon -->
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-gem text-3xl text-green-300"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Pembuatan</h3>
                        <p class="text-white/70 leading-relaxed">Setelah konfirmasi, perhiasan Anda akan dibuat oleh
                            pengrajin berpengalaman kami dengan kualitas terbaik.</p>
                    </div>

                    <!-- Floating Elements -->
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-16 text-center">
                <div
                    class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white/90">
                    <i class="fa-solid fa-clock text-yellow-300"></i>
                    <span class="text-lg font-medium">Estimasi Waktu: 7-14 hari kerja</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Transition Section -->
    <section
        class="relative w-full py-24 bg-gradient-to-br from-white via-slate-50 to-indigo-50 dark:from-slate-100 dark:via-slate-200 dark:to-indigo-100 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
            <!-- Main Content -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-300 rounded-full text-indigo-600 dark:text-indigo-700 text-sm font-medium mb-8">
                    <i class="fa-solid fa-star text-yellow-500"></i>
                    <span>Why Choose Us?</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-900 mb-6">
                    <span class="block">Mengapa</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Memilih
                    </span>
                    <span
                        class="block text-2xl md:text-3xl lg:text-4xl font-light text-slate-600 dark:text-slate-700 mt-2">
                        Kami?
                    </span>
                </h2>

                <p
                    class="text-lg md:text-xl text-slate-600 dark:text-slate-700 max-w-3xl mx-auto leading-relaxed mb-12">
                    Kami berkomitmen memberikan pengalaman terbaik dalam mewujudkan perhiasan custom impian Anda.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="feature-card group relative">
                    <div
                        class="bg-white/80 dark:bg-white/90 backdrop-blur-md border border-white/20 dark:border-slate-200/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-purple-500/20 to-indigo-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-medal text-2xl text-purple-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-900 mb-4">Kualitas Premium</h3>
                        <p class="text-slate-600 dark:text-slate-700 leading-relaxed">Material terbaik dan pengerjaan
                            teliti untuk hasil yang memuaskan.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card group relative">
                    <div
                        class="bg-white/80 dark:bg-white/90 backdrop-blur-md border border-white/20 dark:border-slate-200/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500/20 to-blue-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-users text-2xl text-indigo-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-900 mb-4">Tim Ahli</h3>
                        <p class="text-slate-600 dark:text-slate-700 leading-relaxed">Pengrajin berpengalaman dengan
                            keahlian yang telah teruji.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card group relative">
                    <div
                        class="bg-white/80 dark:bg-white/90 backdrop-blur-md border border-white/20 dark:border-slate-200/50 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-shield-check text-2xl text-green-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-900 mb-4">Garansi Lengkap</h3>
                        <p class="text-slate-600 dark:text-slate-700 leading-relaxed">Jaminan kualitas dan layanan purna
                            jual yang terpercaya.</p>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <div
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border border-indigo-200 dark:border-indigo-300 rounded-2xl mb-8">
                    <i class="fa-solid fa-heart text-red-500"></i>
                    <span class="text-lg font-semibold text-slate-700 dark:text-slate-800">
                        Siap mewujudkan perhiasan impian Anda?
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#custom-form"
                        class="group relative px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-pencil"></i>
                            Mulai Custom Request
                        </span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                        </div>
                    </a>
                    <a href="https://wa.me/6281338887248"
                        class="px-8 py-4 bg-white/80 dark:bg-white/90 backdrop-blur-md border border-slate-200 dark:border-slate-300 hover:bg-white dark:hover:bg-white text-slate-700 dark:text-slate-800 font-medium rounded-full transition-all duration-300 transform hover:scale-105">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-green-500"></i>
                            Konsultasi WhatsApp
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Floating Particles Animation */
        .particle {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.8;
            }
        }

        /* Hero Elements Animation */
        .hero-badge {
            animation: slideInDown 1s ease-out 0.5s both;
        }

        .hero-title {
            animation: slideInUp 1s ease-out 0.7s both;
        }

        .hero-description {
            animation: slideInUp 1s ease-out 0.9s both;
        }

        .hero-buttons {
            animation: slideInUp 1s ease-out 1.1s both;
        }

        .hero-stats {
            animation: slideInUp 1s ease-out 1.3s both;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Input Focus Effects */
        input:focus,
        select:focus,
        textarea:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.15);
        }

        /* Process Cards Hover Effects */
        .process-card:hover .absolute {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        // Preview gambar saat dipilih (<= 2MB)
  document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('file-upload');
    const wrap = document.getElementById('previewWrap');
    const img = document.getElementById('previewImg');
    if (!fileInput) return;
    fileInput.addEventListener('change', function(){
      const file = this.files && this.files[0] ? this.files[0] : null;
      if (!file) { if (wrap) wrap.classList.add('hidden'); return; }
      if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar melebihi 2MB. Silakan pilih file yang lebih kecil.');
        this.value = '';
        if (wrap) wrap.classList.add('hidden');
        return;
      }
      const reader = new FileReader();
      reader.onload = function(ev){ if (img && wrap) { img.src = ev.target.result; wrap.classList.remove('hidden'); } };
      reader.readAsDataURL(file);
    });
  });

    // Cart count initialization for custom request page
    document.addEventListener('DOMContentLoaded', function() {
        if (window.updateCartCount) {
            window.updateCartCount();
        }
    });
    </script>
</div>
