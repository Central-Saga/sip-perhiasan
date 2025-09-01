<?php
use function Livewire\Volt\{ layout };

layout('components.layouts.landing');
?>

<div class="max-w-7xl mx-auto px-4 py-24">
    <!-- Hero / Intro -->
    <section class="mb-12">
        <div class="rounded-2xl bg-gradient-to-br from-indigo-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 border border-slate-200 dark:border-slate-700 p-8 md:p-12 shadow-sm">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100/60 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold mb-3">
                        <i class="fa-solid fa-gem"></i>
                        Tentang Bliss Silversmith
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-800 dark:text-slate-100">Merayakan Keindahan Perak, Dikerjakan dengan Hati</h1>
                    <p class="mt-3 text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">Bliss Silversmith hadir untuk menghadirkan perhiasan perak yang elegan, fungsional, dan bermakna—didesain untuk menyempurnakan momen istimewa sekaligus menemani keseharian Anda.</p>
                </div>
                <div class="flex-1 w-full">
                    <div class="relative rounded-xl overflow-hidden shadow-md">
                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=1200&auto=format&fit=crop" alt="Workshop Bliss Silversmith" class="object-cover w-full h-64 md:h-56">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="mb-16 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Kisah & Filosofi</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Kami percaya bahwa perhiasan terbaik adalah yang memadukan desain apik, kenyamanan, dan makna personal. Di Bliss Silversmith, setiap koleksi dirancang dengan perhatian pada detail—mulai dari sketsa awal, pemilihan material, hingga proses finishing—agar menghasilkan karya yang tahan lama serta menyenangkan dipakai.</p>
            <p class="mt-3 text-slate-600 dark:text-slate-300 leading-relaxed">Nama “Bliss” merepresentasikan rasa bahagia yang ingin kami hadirkan saat Anda menemukan atau menghadiahkan perhiasan yang tepat.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Misi Kami</h2>
            <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                <li class="flex items-start gap-2"><i class="fa-solid fa-check text-indigo-500 mt-1"></i><span>Menyajikan perhiasan silver premium yang nyaman dipakai sehari‑hari.</span></li>
                <li class="flex items-start gap-2"><i class="fa-solid fa-check text-indigo-500 mt-1"></i><span>Mengutamakan detail pengerjaan, ketepatan ukuran, dan ketahanan finishing.</span></li>
                <li class="flex items-start gap-2"><i class="fa-solid fa-check text-indigo-500 mt-1"></i><span>Mendukung personalisasi desain agar tiap karya merefleksikan kepribadian pemiliknya.</span></li>
            </ul>
        </div>
    </section>

    <!-- Values -->
    <section class="mb-16">
        <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Nilai yang Kami Junjung</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Kualitas</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Material pilihan dan kontrol kualitas ketat di tiap tahap produksi.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Desain</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Estetika modern yang tetap nyaman dipakai—dengan proporsi yang seimbang.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-hands-holding"></i>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Servis</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Dukungan purna jual: penyesuaian ukuran, pemolesan, hingga perawatan berkala.</p>
            </div>
        </div>
    </section>

    <!-- Craft Process -->
    <section class="mb-16">
        <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Proses Perajin Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center">
                <div class="text-indigo-600 dark:text-indigo-300 mb-2"><i class="fa-solid fa-pen-ruler"></i></div>
                <p class="font-semibold text-slate-800 dark:text-slate-100">Desain & Sketsa</p>
                <p class="text-xs text-slate-600 dark:text-slate-300">Menjaga proporsi, ergonomi, dan karakter visual.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center">
                <div class="text-indigo-600 dark:text-indigo-300 mb-2"><i class="fa-solid fa-cubes"></i></div>
                <p class="font-semibold text-slate-800 dark:text-slate-100">Pembuatan Komponen</p>
                <p class="text-xs text-slate-600 dark:text-slate-300">Pemotongan, pembentukan, dan penyolderan presisi.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center">
                <div class="text-indigo-600 dark:text-indigo-300 mb-2"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <p class="font-semibold text-slate-800 dark:text-slate-100">Finishing</p>
                <p class="text-xs text-slate-600 dark:text-slate-300">Pemolesan bertahap untuk kilau halus dan tahan lama.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center">
                <div class="text-indigo-600 dark:text-indigo-300 mb-2"><i class="fa-solid fa-shield-heart"></i></div>
                <p class="font-semibold text-slate-800 dark:text-slate-100">Quality Check</p>
                <p class="text-xs text-slate-600 dark:text-slate-300">Pemeriksaan detail sebelum dikemas rapi untuk Anda.</p>
            </div>
        </div>
    </section>

    <!-- Materials & Care -->
    <section class="mb-16 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Material</h2>
            <p class="text-slate-600 dark:text-slate-300">Kami mengutamakan perak berkualitas dengan standar yang nyaman dipakai dan mudah dirawat. Untuk aksen, kami memilih batu zirkonia/kristal berkualitas atau batu natural sesuai ketersediaan dan desain.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Perawatan</h2>
            <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                <li class="flex items-start gap-2"><i class="fa-solid fa-circle-dot text-indigo-500 mt-1"></i><span>Simpan di pouch/kotak kering, hindari lembap berlebih.</span></li>
                <li class="flex items-start gap-2"><i class="fa-solid fa-circle-dot text-indigo-500 mt-1"></i><span>Bersihkan ringan dengan kain lap khusus perak setelah digunakan.</span></li>
                <li class="flex items-start gap-2"><i class="fa-solid fa-circle-dot text-indigo-500 mt-1"></i><span>Hindari paparan bahan kimia keras, parfum berlebih, dan air laut.</span></li>
            </ul>
        </div>
    </section>

    <!-- CTA -->
    <section class="rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 p-8 text-center">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Punya ide desain impian?</h3>
        <p class="text-slate-600 dark:text-slate-300 mt-1">Kami menerima custom request—dari sketsa sederhana hingga personalisasi detail.</p>
        <a href="{{ route('custom') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-semibold shadow">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            Buat Custom Request
        </a>
    </section>
</div>

