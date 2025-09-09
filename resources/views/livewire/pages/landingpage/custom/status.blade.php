<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\CustomRequest;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

state(['customRequests' => [], 'isLoading' => true]);

mount(function () {
    $this->loadCustomRequests();
});

$loadCustomRequests = function () {
    if (!Auth::check()) {
        $this->customRequests = collect();
        $this->isLoading = false;
        return;
    }

    $user = Auth::user();
    $pelanggan = Pelanggan::where('user_id', $user->id)->first();

    if ($pelanggan) {
        $this->customRequests = CustomRequest::where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    $this->isLoading = false;
};

$approveRequest = function($id) {
    $customRequest = CustomRequest::find($id);
    if ($customRequest && $customRequest->status === 'price_proposed') {
        $customRequest->update(['status' => 'approved']);

        // Add to cart after approval
        \App\Models\Keranjang::create([
            'pelanggan_id' => $customRequest->pelanggan_id,
            'custom_request_id' => $customRequest->id,
            'jumlah' => 1,
            'harga_satuan' => $customRequest->estimasi_harga,
            'subtotal' => $customRequest->estimasi_harga,
        ]);

        session()->flash('success', 'Custom request disetujui dan ditambahkan ke keranjang!');
        $this->loadCustomRequests();
    }
};

$rejectRequest = function($id) {
    $customRequest = CustomRequest::find($id);
    if ($customRequest && $customRequest->status === 'price_proposed') {
        $customRequest->update(['status' => 'rejected']);
        session()->flash('success', 'Custom request ditolak.');
        $this->loadCustomRequests();
    }
};
?>

<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div
        class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Hero Section -->
    <section
        class="relative min-h-[40vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 text-center">
            <div class="space-y-8">
                <!-- Badge -->
                <div
                    class="hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                    <i class="fa-solid fa-clipboard-list text-purple-300"></i>
                    <span>Status Custom Request</span>
                </div>

                <!-- Main Heading -->
                <div class="space-y-4">
                    <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        <span class="block">Status</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Custom Request
                        </span>
                    </h1>
                </div>

                <!-- Description -->
                <p class="hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                    Pantau perkembangan custom request Anda dan kelola pesanan perhiasan impian.
                </p>
            </div>
        </div>
    </section>

    <!-- Custom Requests Section -->
    <section
        class="relative w-full py-20 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Daftar</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Custom Request
                    </span>
                </h2>
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Kelola dan pantau semua custom request yang telah Anda kirimkan.
                </p>
            </div>

            <!-- Loading State -->
            @if($isLoading)
            <div class="text-center py-16">
                <div class="inline-flex items-center gap-3 text-slate-600 dark:text-slate-300">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                    <span class="text-lg">Memuat data...</span>
                </div>
            </div>
            @elseif($customRequests && count($customRequests) > 0)
            <!-- Custom Requests List -->
            <div class="space-y-8">
                @foreach($customRequests as $index => $request)
                <div
                    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl p-8 shadow-xl">
                    <div class="flex items-start gap-8">
                        <!-- Request Image -->
                        <div
                            class="w-32 h-32 flex-shrink-0 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                            @if($request->gambar_referensi)
                            <img src="{{ asset('storage/' . $request->gambar_referensi) }}"
                                class="w-full h-full object-cover" alt="Referensi Custom Request" />
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-image text-4xl"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Request Details -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                                        Custom Request #{{ $request->id }}
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Dibuat: {{ $request->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                @php
                                $statusConfig = [
                                'pending' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                                'reviewed' => ['color' => 'blue', 'icon' => 'eye', 'text' => 'Sedang Direview'],
                                'price_proposed' => ['color' => 'purple', 'icon' => 'dollar-sign', 'text' => 'Penawaran
                                Harga'],
                                'approved' => ['color' => 'green', 'icon' => 'check', 'text' => 'Disetujui'],
                                'rejected' => ['color' => 'red', 'icon' => 'times', 'text' => 'Ditolak'],
                                'in_progress' => ['color' => 'indigo', 'icon' => 'cog', 'text' => 'Sedang Dikerjakan'],
                                'completed' => ['color' => 'emerald', 'icon' => 'check-circle', 'text' => 'Selesai'],
                                'cancelled' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Dibatalkan']
                                ];
                                $config = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                @endphp

                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 px-4 py-2 text-sm font-medium text-{{ $config['color'] }}-700 dark:text-{{ $config['color'] }}-300 ring-1 ring-inset ring-{{ $config['color'] }}-700/10">
                                    <i class="fa-solid fa-{{ $config['icon'] }}"></i>
                                    {{ $config['text'] }}
                                </span>
                            </div>

                            <!-- Request Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-tag text-slate-400"></i>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Kategori:</span>
                                    <span class="text-slate-600 dark:text-slate-400">{{ $request->kategori }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-cube text-slate-400"></i>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Material:</span>
                                    <span class="text-slate-600 dark:text-slate-400">{{ $request->material }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-expand text-slate-400"></i>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Ukuran:</span>
                                    <span class="text-slate-600 dark:text-slate-400">{{ $request->ukuran ?: '-'
                                        }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-weight text-slate-400"></i>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Berat:</span>
                                    <span class="text-slate-600 dark:text-slate-400">{{ $request->berat }} gram</span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($request->deskripsi)
                            <div class="mb-6">
                                <h4 class="font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi:</h4>
                                <p
                                    class="text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-700 p-4 rounded-xl">
                                    {{ $request->deskripsi }}
                                </p>
                            </div>
                            @endif

                            <!-- Price Information -->
                            @if($request->estimasi_harga > 0)
                            <div
                                class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl">
                                <div class="flex items-center gap-3 text-green-700 dark:text-green-300">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    <span class="font-semibold">Estimasi Harga:</span>
                                    <span class="text-xl font-bold">Rp {{ number_format($request->estimasi_harga, 0,
                                        ',', '.') }}</span>
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-4">
                                @if($request->status === 'price_proposed')
                                <button wire:click="approveRequest({{ $request->id }})"
                                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i>
                                    Setujui & Tambah ke Keranjang
                                </button>
                                <button wire:click="rejectRequest({{ $request->id }})"
                                    class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all duration-300 flex items-center gap-2">
                                    <i class="fa-solid fa-times"></i>
                                    Tolak Penawaran
                                </button>
                                @elseif($request->status === 'approved')
                                <a href="{{ route('cart') }}"
                                    class="px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-xl transition-all duration-300 flex items-center gap-2">
                                    <i class="fa-solid fa-shopping-cart"></i>
                                    Lihat di Keranjang
                                </a>
                                @elseif($request->status === 'completed')
                                <a href="{{ route('transaksi') }}"
                                    class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all duration-300 flex items-center gap-2">
                                    <i class="fa-solid fa-receipt"></i>
                                    Lihat Transaksi
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="relative">
                    <div
                        class="h-32 w-32 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse mx-auto">
                        <i class="fa-solid fa-clipboard-list text-6xl text-slate-400"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-slate-900 dark:text-white">Belum Ada Custom Request</h3>
                <p class="mt-2 text-lg text-slate-500 dark:text-slate-400">Mulai dengan membuat custom request pertama
                    Anda</p>
                <a href="{{ route('custom') }}"
                    class="mt-6 inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full transition-all duration-300">
                    <i class="fa-solid fa-plus"></i>
                    Buat Custom Request
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Back to Home -->
    <div class="text-center py-16">
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <style>
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

        /* Custom request cards hover effects */
        .bg-white\/80:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Status badge pulse animation */
        .inline-flex.items-center.gap-2.rounded-full {
            transition: all 0.3s ease;
        }

        .inline-flex.items-center.gap-2.rounded-full:hover {
            transform: scale(1.05);
        }

        /* Button hover effects */
        button,
        a[href] {
            transition: all 0.3s ease;
        }

        button:hover,
        a[href]:hover {
            transform: scale(1.05);
        }

        /* Image hover effects */
        img {
            transition: all 0.3s ease;
        }

        img:hover {
            transform: scale(1.05);
        }

        /* Loading animation */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Notification animation */
        .fixed.top-4.right-4 {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
    </style>

    <!-- Status Animations -->
    <script src="{{ asset('js/status-animations.js') }}"></script>

    <script>
        // Auto-hide notifications with GSAP animation
        setTimeout(() => {
            const notifications = document.querySelectorAll('.fixed.top-4.right-4');
            notifications.forEach(notification => {
                if (typeof gsap !== 'undefined') {
                    gsap.to(notification, {
                        opacity: 0,
                        x: 100,
                        duration: 0.3,
                        ease: "power2.out",
                        onComplete: () => notification.remove()
                    });
                } else {
                    // Fallback for when GSAP is not loaded
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }
            });
        }, 5000);
    </script>
</div>