<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

state(['keranjangItems' => [], 'customRequests' => [], 'total' => 0]);

mount(function () {
    $this->loadCartData();
});

$loadCartData = function () {
    if (!Auth::check()) {
        $this->keranjangItems = collect();
        $this->customRequests = collect();
        $this->total = 0;
        return;
    }

    $user = Auth::user();
    $pelanggan = Pelanggan::where('user_id', $user->id)->first();

    if (!$pelanggan) {
        $this->keranjangItems = collect();
        $this->customRequests = collect();
        $this->total = 0;
        return;
    }

    // Load cart items from database
    $this->keranjangItems = Keranjang::with(['produk', 'customRequest'])
        ->where('pelanggan_id', $pelanggan->id)
        ->get();


    // Calculate total
    $this->total = 0;
    foreach ($this->keranjangItems as $item) {
        if ($item->produk_id) {
            // If harga_satuan is null, try to get it from produk
            if (!$item->harga_satuan && $item->produk) {
                $item->harga_satuan = $item->produk->harga;
                $item->subtotal = $item->harga_satuan * $item->jumlah;
                $item->save();
            }
            $this->total += $item->subtotal ?? ($item->harga_satuan * $item->jumlah);
        } elseif ($item->custom_request_id && $item->customRequest && $item->customRequest->status === 'approved') {
            // Include approved custom requests in total (custom request is always quantity 1)
            $this->total += $item->harga_satuan ?? 0;
        }
    }

    // Get only approved custom requests (should be in cart)
    $this->customRequests = $this->keranjangItems->where('custom_request_id', '!=', null)->map(function($item) {
        return $item->customRequest;
    })->filter(function($customRequest) {
        return $customRequest && $customRequest->status === 'approved';
    });
};


$updateQty = function($id, $delta) {
    $item = Keranjang::find($id);
    if (!$item) return;

    $newQty = $item->jumlah + $delta;
    if ($newQty <= 0) {
        $item->delete();
    } else {
        // Cek stok produk
        if ($newQty > $item->produk->stok) {
            session()->flash('error', 'Jumlah yang diminta melebihi stok yang tersedia. Stok tersisa: ' . $item->produk->stok);
            return;
        }

        // Ensure harga_satuan is set
        if (!$item->harga_satuan && $item->produk) {
            $item->harga_satuan = $item->produk->harga;
        }

        $item->update([
            'jumlah' => $newQty,
            'subtotal' => $newQty * $item->harga_satuan
        ]);
    }

    $this->dispatch('cart-updated');
    // Refresh halaman untuk update cart count di header
    $this->redirect(route('cart'), navigate: true);
};

$removeItem = function($id) {
    $item = Keranjang::find($id);
    if ($item) {
        $item->delete();
        $this->dispatch('cart-updated');
        // Refresh halaman untuk update cart count di header
        $this->redirect(route('cart'), navigate: true);
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
            <div class="product-bg-1 absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="product-bg-2 absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl">
            </div>
            <div
                class="product-bg-3 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl">
            </div>
        </div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="product-particle absolute w-2 h-2 bg-white/30 rounded-full"
                style="top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="product-particle absolute w-1 h-1 bg-purple-300/40 rounded-full"
                style="top: 60%; left: 20%; animation-delay: 1s;"></div>
            <div class="product-particle absolute w-3 h-3 bg-indigo-300/30 rounded-full"
                style="top: 30%; right: 15%; animation-delay: 2s;"></div>
            <div class="product-particle absolute w-2 h-2 bg-pink-300/40 rounded-full"
                style="bottom: 30%; left: 30%; animation-delay: 3s;"></div>
            <div class="product-particle absolute w-1 h-1 bg-white/20 rounded-full"
                style="bottom: 20%; right: 25%; animation-delay: 4s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 text-center">
            <div class="space-y-8">
                <!-- Badge -->
                <div
                    class="product-hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                    <i class="fa-solid fa-shopping-cart text-purple-300"></i>
                    <span>Keranjang Belanja</span>
                </div>

                <!-- Main Heading -->
                <div class="space-y-4">
                    <h1 class="product-hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        <span class="block">Keranjang</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Belanja
                        </span>
                        <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                            Bliss Silversmith
                        </span>
                    </h1>
                </div>

                <!-- Description -->
                <p class="product-hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                    Kelola produk yang akan Anda beli dengan mudah dan nyaman.
                    Pilihan terbaik untuk melengkapi koleksi perhiasan Anda.
                </p>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-6xl mx-auto px-6">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <span>Cart Items</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Produk di</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Keranjang
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Kelola produk yang akan Anda beli dengan mudah dan nyaman.
                </p>
            </div>

            <!-- Cart Content -->
            <div
                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl">
                @if($keranjangItems && count($keranjangItems) > 0)
                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($keranjangItems as $item)
                    @if($item->produk)
                    <div class="p-8 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition duration-300">
                        <div class="flex gap-8">
                            <!-- Product Image -->
                            <div class="w-32 h-32 md:w-36 md:h-36 flex-shrink-0">
                                @if($item->produk->foto)
                                <div class="relative group">
                                    <img src="{{ Storage::url($item->produk->foto) }}"
                                        alt="{{ $item->produk->nama_produk }}"
                                        class="w-full h-full object-cover rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 cursor-pointer group-hover:scale-105 transition-transform duration-300"
                                        onclick="openImageModal('{{ Storage::url($item->produk->foto) }}', '{{ $item->produk->nama_produk }}')" />
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-2xl transition-all duration-300">
                                    </div>
                                </div>
                                @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-2xl border-2 border-dashed border-purple-200 dark:border-purple-700">
                                    <i class="fa-solid fa-gem text-4xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-slate-100 text-2xl mb-2">{{
                                            $item->produk->nama_produk }}</h4>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 px-3 py-1 text-sm font-medium text-indigo-700 dark:text-indigo-300 ring-1 ring-inset ring-indigo-700/10">
                                                <i class="fa-solid fa-tag text-xs"></i>
                                                {{ $item->produk->kategori ?? '-' }}
                                            </span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">ID: {{
                                                $item->produk->id }}</span>
                                        </div>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})"
                                        class="p-3 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-300 hover:scale-110"
                                        title="Hapus" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </div>

                                <div class="flex justify-between items-center">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center gap-4">
                                        <span
                                            class="text-lg font-medium text-slate-700 dark:text-slate-300">Jumlah:</span>
                                        <div class="flex items-center gap-3">
                                            <button wire:click="updateQty({{ $item->id }}, -1)"
                                                class="w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:scale-110">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <span
                                                class="text-lg font-bold text-slate-900 dark:text-white min-w-[3rem] text-center">{{
                                                $item->jumlah }}</span>
                                            <button wire:click="updateQty({{ $item->id }}, 1)"
                                                class="w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:scale-110">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="text-right">
                                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Harga Satuan</div>
                                        <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                                            Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Subtotal</div>
                                        <div class="text-2xl font-black text-slate-900 dark:text-white">
                                            Rp {{ number_format($item->subtotal ?? (($item->harga_satuan ?? 0) *
                                            $item->jumlah), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($item->customRequest && $item->customRequest->status === 'approved')
                    <!-- Custom Request Item -->
                    <div class="p-8 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition duration-300">
                        <div class="flex gap-8">
                            <!-- Custom Request Image -->
                            <div
                                class="w-32 h-32 md:w-36 md:h-36 flex-shrink-0 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                                @if($item->customRequest->gambar_referensi)
                                <img src="{{ Storage::url($item->customRequest->gambar_referensi) }}"
                                    alt="Custom Request"
                                    class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                    onclick="openImageModal('{{ Storage::url($item->customRequest->gambar_referensi) }}', 'Custom Request')" />
                                @else
                                <div class="w-full h-full flex items-center justify-center text-purple-400">
                                    <i class="fa-solid fa-wand-magic-sparkles text-4xl"></i>
                                </div>
                                @endif
                            </div>

                            <!-- Custom Request Details -->
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-slate-100 text-2xl mb-2">
                                            Custom Request
                                        </h4>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-purple-100 dark:bg-purple-900/30 px-3 py-1 text-sm font-medium text-purple-700 dark:text-purple-300 ring-1 ring-inset ring-purple-700/10">
                                                <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                                                {{ $item->customRequest->kategori ?? 'Custom' }}
                                            </span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">ID: {{
                                                $item->customRequest->id }}</span>
                                        </div>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})"
                                        class="p-3 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-300 hover:scale-110"
                                        title="Hapus"
                                        onclick="return confirm('Hapus custom request ini dari keranjang?')">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </div>

                                <div class="flex justify-between items-center">
                                    <!-- Custom Request Info -->
                                    <div class="flex items-center gap-4">
                                        <span class="text-lg font-medium text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-wand-magic-sparkles text-purple-500 mr-2"></i>
                                            Custom Request
                                        </span>
                                        <span class="text-sm text-slate-500 dark:text-slate-400">
                                            Status: {{ ucfirst(str_replace('_', ' ', $item->customRequest->status)) }}
                                        </span>
                                    </div>

                                    <!-- Price -->
                                    <div class="text-right">
                                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Estimasi Harga
                                        </div>
                                        <div class="text-2xl font-black text-purple-600 dark:text-purple-400">
                                            Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <div class="p-16 text-center">
                    <div class="relative">
                        <div
                            class="h-32 w-32 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse mx-auto">
                            <i class="fa-solid fa-shopping-cart text-6xl text-slate-400"></i>
                        </div>
                        <div
                            class="absolute -right-3 -bottom-3 h-10 w-10 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800">
                            <i class="fa-solid fa-plus text-slate-400"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-2xl font-bold text-slate-900 dark:text-white">Keranjang Belanja Kosong</h3>
                    <p class="mt-2 text-lg text-slate-500 dark:text-slate-400">Mulai dengan menambahkan produk ke
                        keranjang</p>
                </div>
                @endif


                <!-- Summary Section -->
                <div class="border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-8">
                            <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">Total Pembayaran</div>
                            <div class="text-right">
                                <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400">Rp {{
                                    number_format($total, 0, ',', '.') }}</div>
                                <div class="text-lg text-slate-500 dark:text-slate-400">{{ $keranjangItems->count() }}
                                    item</div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('produk') }}"
                                class="flex-1 sm:flex-none sm:px-8 sm:py-4 px-6 py-4 rounded-2xl bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold transition-all duration-300 flex items-center justify-center gap-3 text-lg border border-slate-200 dark:border-slate-600 hover:shadow-lg hover:scale-105">
                                <i class="fa-solid fa-arrow-left"></i>
                                Lanjut Belanja
                            </a>
                            @if($keranjangItems->count() > 0)
                            <a href="{{ route('checkout') }}"
                                class="flex-1 sm:flex-none sm:px-8 sm:py-4 px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-2xl font-bold transition-all duration-300 flex items-center justify-center gap-3 text-lg shadow-xl hover:shadow-2xl transform hover:scale-105">
                                <i class="fa-solid fa-credit-card"></i>
                                Proses Checkout
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-16">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 id="modalTitle" class="text-xl font-bold text-slate-900 dark:text-white"></h3>
                    <button onclick="closeImageModal()"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors duration-200">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-96 object-contain rounded-xl">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update cart count when cart page loads
    document.addEventListener('DOMContentLoaded', function() {
        if (window.updateCartCount) {
            updateCartCount();
        }
    });

    // Also update when Livewire navigates to this page
    document.addEventListener('livewire:navigated', function() {
        if (window.updateCartCount) {
            setTimeout(() => {
                updateCartCount();
            }, 100);
        }
    });

    // Auto-hide notifications
    setTimeout(() => {
        const notifications = document.querySelectorAll('.fixed.top-4.right-4');
        notifications.forEach(notification => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        });
    }, 3000);

    // Image modal functions
    function openImageModal(imageSrc, productName) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').textContent = productName;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>