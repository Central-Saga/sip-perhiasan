<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

state(['transaksi' => null]);

mount(function ($id) {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    $pelanggan = Pelanggan::where('user_id', $user->id)->first();

    if (!$pelanggan) {
        return redirect()->route('home');
    }

    $this->transaksi = Transaksi::with(['detailTransaksi.produk', 'pengiriman', 'pembayaran', 'pelanggan.user'])
        ->where('id', $id)
        ->where('pelanggan_id', $pelanggan->id)
        ->first();

    if (!$this->transaksi) {
        abort(404, 'Transaksi tidak ditemukan');
    }
});

function getStatusColor($status) {
    return match($status) {
        'Pending' => 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/20 dark:to-amber-900/20 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
        'Diproses' => 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 dark:from-blue-900/20 dark:to-indigo-900/20 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
        'Selesai' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/20 dark:to-emerald-900/20 dark:text-green-300 border border-green-200 dark:border-green-800',
        'Dibatalkan' => 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 dark:from-red-900/20 dark:to-pink-900/20 dark:text-red-300 border border-red-200 dark:border-red-800',
        default => 'bg-gradient-to-r from-slate-100 to-gray-100 text-slate-800 dark:from-slate-700 dark:to-gray-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600'
    };
}

function getStatusIcon($status) {
    return match($status) {
        'Pending' => 'fa-clock',
        'Diproses' => 'fa-cog fa-spin',
        'Selesai' => 'fa-check-circle',
        'Dibatalkan' => 'fa-times-circle',
        default => 'fa-question-circle'
    };
}
?>
<div>
    <div>
        <!-- Hero Section -->
        <section
            class="relative min-h-[40vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
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
            <div class="relative z-10 w-full max-w-7xl mx-auto px-6 text-center">
                <div class="space-y-8">
                    <!-- Badge -->
                    <div
                        class="hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                        <i class="fa-solid fa-file-invoice text-purple-300"></i>
                        <span>Detail Transaksi</span>
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-4">
                        <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                            <span class="block">Detail</span>
                            <span
                                class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                                Transaksi
                            </span>
                            <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                                {{ $transaksi->kode_transaksi }}
                            </span>
                        </h1>
                    </div>

                    <!-- Description -->
                    <p class="hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                        Informasi lengkap tentang transaksi dan pesanan Anda.
                        Pantau status dan detail pembelian dengan mudah.
                    </p>
                </div>
            </div>
        </section>

        <!-- Detail Section -->
        <section
            class="relative w-full py-20 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <!-- Back Button -->
                <div class="mb-8">
                    <a href="{{ route('transaksi') }}"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Transaksi</span>
                    </a>
                </div>

                <!-- Main Transaction Card -->
                <div class="group relative mb-8">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="p-8 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div
                                            class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                                            <i class="fa-solid fa-receipt text-white text-2xl"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-3xl font-bold text-slate-800 dark:text-slate-100">
                                                {{ $transaksi->kode_transaksi }}
                                            </h2>
                                            <p class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                                <i class="fa-solid fa-calendar text-sm"></i>
                                                {{ $transaksi->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col lg:items-end gap-4">
                                    <div class="text-right">
                                        <div
                                            class="text-4xl font-black text-transparent bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text">
                                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">Total Pembayaran</div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-4 py-2 rounded-full text-sm font-bold {{ getStatusColor($transaksi->status) }}">
                                            <i class="fa-solid {{ getStatusIcon($transaksi->status) }} mr-2"></i>
                                            {{ ucfirst($transaksi->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-8">
                            <!-- Customer & Payment Info -->
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                <!-- Customer Info -->
                                <div
                                    class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <h3
                                        class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-user text-blue-500"></i>
                                        Informasi Pelanggan
                                    </h3>
                                    <div class="space-y-3 text-slate-600 dark:text-slate-300">
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-user-circle text-blue-500 w-5"></i>
                                            <span><strong>Nama:</strong> {{ $transaksi->pelanggan->user->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-envelope text-blue-500 w-5"></i>
                                            <span><strong>Email:</strong> {{ $transaksi->pelanggan->user->email
                                                }}</span>
                                        </div>
                                        @if($transaksi->pelanggan->no_telepon)
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-phone text-blue-500 w-5"></i>
                                            <span><strong>Telepon:</strong> {{ $transaksi->pelanggan->no_telepon
                                                }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                @if($transaksi->pembayaran)
                                <div
                                    class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                    <h3
                                        class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-credit-card text-green-500"></i>
                                        Informasi Pembayaran
                                    </h3>
                                    <div class="space-y-3 text-slate-600 dark:text-slate-300">
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-credit-card text-green-500 w-5"></i>
                                            <span><strong>Metode:</strong>
                                                <span
                                                    class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded text-xs font-semibold ml-2">
                                                    {{ ucfirst($transaksi->pembayaran->metode) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-info-circle text-green-500 w-5"></i>
                                            <span><strong>Status:</strong>
                                                <span
                                                    class="px-2 py-1 rounded text-xs font-semibold ml-2 {{ getStatusColor($transaksi->pembayaran->status) }}">
                                                    {{ ucfirst($transaksi->pembayaran->status) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if($transaksi->pembayaran->tanggal_bayar)
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-calendar text-green-500 w-5"></i>
                                            <span><strong>Tanggal Bayar:</strong> {{
                                                $transaksi->pembayaran->tanggal_bayar->format('d M Y, H:i') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
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

                <!-- Products Section -->
                <div class="group relative mb-8">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                        <div class="p-8">
                            <h3
                                class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                                <i class="fa-solid fa-gem text-indigo-500"></i>
                                Produk yang Dibeli
                            </h3>

                            <div class="space-y-6">
                                @foreach($transaksi->detailTransaksi as $detail)
                                <div
                                    class="flex items-center gap-6 p-6 bg-gradient-to-r from-slate-50 to-indigo-50 dark:from-slate-700/50 dark:to-indigo-900/20 rounded-xl border border-slate-200 dark:border-slate-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-24 h-24 flex-shrink-0">
                                        @if($detail->produk && $detail->produk->foto)
                                        <img src="{{ Storage::url($detail->produk->foto) }}"
                                            alt="{{ $detail->produk->nama_produk }}"
                                            class="w-full h-full object-cover rounded-xl border border-slate-200 dark:border-slate-600">
                                        @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl border-2 border-dashed border-purple-200 dark:border-purple-700">
                                            <i
                                                class="fa-solid fa-gem text-3xl text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">
                                            {{ $detail->produk ? $detail->produk->nama_produk : 'Produk Tidak Tersedia'
                                            }}
                                        </h4>
                                        @if($detail->produk && $detail->produk->kategori)
                                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                                            <i class="fa-solid fa-tag mr-1"></i>
                                            Kategori: {{ $detail->produk->kategori }}
                                        </div>
                                        @endif
                                        <div class="flex items-center gap-6">
                                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                                <i class="fa-solid fa-hashtag"></i>
                                                <span>{{ $detail->jumlah }} pcs</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                                <i class="fa-solid fa-tag"></i>
                                                <span>@ {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">Subtotal</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-slate-800 dark:text-slate-100">Total
                                        Pembayaran:</span>
                                    <span
                                        class="text-3xl font-black text-transparent bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text">
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </span>
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

                <!-- Shipping Information -->
                @if($transaksi->pengiriman)
                <div class="group relative mb-8">
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                        <div class="p-8">
                            <h3
                                class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                                <i class="fa-solid fa-shipping-fast text-blue-500"></i>
                                Informasi Pengiriman
                            </h3>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div
                                    class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <h4
                                        class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-map-marker-alt text-blue-500"></i>
                                        Alamat Pengiriman
                                    </h4>
                                    <div class="space-y-3 text-slate-600 dark:text-slate-300">
                                        <div class="flex items-start gap-3">
                                            <i class="fa-solid fa-user text-blue-500 w-5 mt-1"></i>
                                            <div>
                                                <strong>Penerima:</strong><br>
                                                {{ $transaksi->pengiriman->nama_penerima }}
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="fa-solid fa-map-marker-alt text-blue-500 w-5 mt-1"></i>
                                            <div>
                                                <strong>Alamat:</strong><br>
                                                {{ $transaksi->pengiriman->alamat_pengiriman }}
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="fa-solid fa-phone text-blue-500 w-5 mt-1"></i>
                                            <div>
                                                <strong>Telepon:</strong><br>
                                                {{ $transaksi->pengiriman->no_telepon }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                    <h4
                                        class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-info-circle text-green-500"></i>
                                        Status Pengiriman
                                    </h4>
                                    <div class="space-y-3 text-slate-600 dark:text-slate-300">
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-truck text-green-500 w-5"></i>
                                            <span><strong>Status:</strong>
                                                <span
                                                    class="px-2 py-1 rounded text-xs font-semibold ml-2 {{ getStatusColor($transaksi->pengiriman->status) }}">
                                                    {{ ucfirst($transaksi->pengiriman->status) }}
                                                </span>
                                            </span>
                                        </div>
                                        @if($transaksi->pengiriman->catatan)
                                        <div class="flex items-start gap-3">
                                            <i class="fa-solid fa-sticky-note text-green-500 w-5 mt-1"></i>
                                            <div>
                                                <strong>Catatan:</strong><br>
                                                {{ $transaksi->pengiriman->catatan }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
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
                @endif

                <!-- Action Buttons -->
                <div class="text-center">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('transaksi') }}"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali ke Transaksi</span>
                        </a>

                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-slate-700 dark:text-slate-300 font-medium rounded-full transition-all duration-300 transform hover:scale-105">
                            <i class="fa-solid fa-home"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .particle {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-badge {
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-title {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-description {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>
