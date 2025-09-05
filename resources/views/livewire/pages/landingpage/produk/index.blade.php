<?php
use function Livewire\Volt\{ layout, state, mount, action };
use App\Models\Produk;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

layout('components.layouts.landing');

state([
    'produkList' => [],
]);

mount(function () {
    $this->produkList = Produk::where('status', true)->get();
});

$addToCart = action(function ($produkId) {
    if (!Auth::check()) {
        session()->flash('error', 'Anda harus login terlebih dahulu.');
        return;
    }

    // Cek apakah user memiliki profil pelanggan
    $pelanggan = Auth::user()->pelanggan;
    if (!$pelanggan) {
        session()->flash('error', 'Profil pelanggan tidak ditemukan. Silakan hubungi administrator.');
        return;
    }

    $produk = Produk::find($produkId);
    if (!$produk) {
        session()->flash('error', 'Produk tidak ditemukan.');
        return;
    }

    // Cek stok produk
    if ($produk->stok <= 0) {
        session()->flash('error', 'Maaf, produk ini sedang tidak tersedia.');
        return;
    }

    try {
        // Cek apakah produk sudah ada di keranjang pelanggan
        $existingCart = Keranjang::where('pelanggan_id', $pelanggan->id)
            ->where('produk_id', $produk->id)
            ->first();

        if ($existingCart) {
            // Cek apakah jumlah di keranjang + 1 tidak melebihi stok
            if (($existingCart->jumlah + 1) > $produk->stok) {
                session()->flash('error', 'Jumlah yang diminta melebihi stok yang tersedia. Stok tersisa: ' . $produk->stok);
                return;
            }

            // Update jumlah jika sudah ada
            $existingCart->increment('jumlah');
            $existingCart->update([
                'subtotal' => $existingCart->jumlah * $existingCart->harga_satuan
            ]);

            session()->flash('success', 'Jumlah produk di keranjang berhasil diperbarui.');
        } else {
            // Buat baru jika belum ada
            Keranjang::create([
                'pelanggan_id' => $pelanggan->id,
                'produk_id' => $produk->id,
                'jumlah' => 1,
                'harga_satuan' => $produk->harga,
                'subtotal' => $produk->harga,
            ]);

            session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        // Dispatch event untuk update cart count
        $this->dispatch('cart-updated');

        // Redirect ke halaman keranjang
        $this->redirect(route('cart'), navigate: true);

    } catch (\Exception $e) {
        session()->flash('error', 'Gagal menambahkan ke keranjang: ' . $e->getMessage());
    }
});
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
        class="relative min-h-[60vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
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
                    <i class="fa-solid fa-gem text-purple-300"></i>
                    <span>Premium Collection</span>
                </div>

                <!-- Main Heading -->
                <div class="space-y-4">
                    <h1 class="product-hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        <span class="block">Koleksi</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Perhiasan
                        </span>
                        <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                            Bliss Silversmith
                        </span>
                    </h1>
                </div>

                <!-- Description -->
                <p class="product-hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                    Temukan koleksi perhiasan silver eksklusif dengan desain modern dan elegan.
                    Pilihan terbaik untuk hadiah dan penyempurna gaya Anda.
                </p>

                <!-- Stats -->
                <div class="product-hero-stats grid grid-cols-3 gap-6 pt-8 border-t border-white/10 max-w-md mx-auto">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white">{{ count($this->produkList) }}+</div>
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
        </div>
    </section>

    <!-- Products Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
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
                    <span>All Products</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Semua</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Produk
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Jelajahi koleksi lengkap perhiasan silver premium kami dengan desain eksklusif dan kualitas terbaik.
                </p>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button
                    class="category-tab active px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg"
                    data-category="all">
                    <i class="fa-solid fa-gem mr-2"></i>
                    Semua Produk
                </button>
                @php
                $categories = $this->produkList->pluck('kategori')->unique()->filter()->values();
                @endphp
                @foreach($categories as $category)
                <button
                    class="category-tab px-6 py-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 font-medium rounded-full transition-all duration-300 transform hover:scale-105 hover:bg-indigo-500 hover:text-white"
                    data-category="{{ $category }}">
                    <i class="fa-solid fa-gem mr-2"></i>
                    {{ $category }}
                </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="products-container">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="products-grid">
                    @foreach($this->produkList as $index => $produk)
                    <div class="product-card group relative" data-index="{{ $index }}"
                        data-category="{{ $produk->kategori ?? 'Premium' }}">
                        <!-- Product Card -->
                        <div
                            class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            <!-- Image Container -->
                            <div class="relative overflow-hidden">
                                <div
                                    class="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                                    @if($produk->foto)
                                    <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk ?? '' }}"
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
                                        <a href="{{ route('produk.detail', $produk->id) }}"
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
                                    <div class="flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400">
                                        <i class="fa-solid fa-box"></i>
                                        <span>{{ $produk->stok ?? 0 }} tersedia</span>
                                    </div>
                                </div>

                                <!-- Features -->
                                <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-300 mb-4">
                                    <div class="flex items-center gap-1">
                                        <i class="fa-solid fa-medal text-indigo-500"></i>
                                        <span>Premium</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="fa-solid fa-shipping-fast text-green-500"></i>
                                        <span>Gratis Ongkir</span>
                                    </div>
                                </div>

                                <!-- Add to Cart Button -->
                                @auth
                                <button wire:click="addToCart({{ $produk->id }})"
                                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Tambah ke Keranjang</span>
                                </button>
                                @else
                                <a href="{{ route('login') }}"
                                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Login untuk Belanja</span>
                                </a>
                                @endauth
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
</div>

<script>
    // Util format
function rupiah(n){ try { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); } catch(e){ return 'Rp 0'; } }

document.addEventListener('DOMContentLoaded', function(){
  updateCartCount();

  // Auto-hide notifications
  setTimeout(() => {
    const notifications = document.querySelectorAll('.fixed.top-4.right-4');
    notifications.forEach(notification => {
      notification.style.opacity = '0';
      notification.style.transform = 'translateX(100%)';
      setTimeout(() => notification.remove(), 300);
    });
  }, 3000);

  // Listen for cart updates from Livewire
  document.addEventListener('livewire:init', () => {
    Livewire.on('cart-updated', () => {
      updateCartCount();
    });
  });

  // Category filter functionality
  const categoryTabs = document.querySelectorAll('.category-tab');
  const productCards = document.querySelectorAll('.product-card');

  categoryTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const selectedCategory = this.dataset.category;

      // Update active tab
      categoryTabs.forEach(t => {
        t.classList.remove('active', 'bg-gradient-to-r', 'from-indigo-500', 'to-purple-500', 'text-white', 'shadow-lg');
        t.classList.add('bg-white/80', 'dark:bg-slate-800/80', 'text-slate-700', 'dark:text-slate-300');
      });

      this.classList.add('active', 'bg-gradient-to-r', 'from-indigo-500', 'to-purple-500', 'text-white', 'shadow-lg');
      this.classList.remove('bg-white/80', 'dark:bg-slate-800/80', 'text-slate-700', 'dark:text-slate-300');

      // Filter products with GSAP animation
      productCards.forEach((card, index) => {
        const cardCategory = card.dataset.category;
        const shouldShow = selectedCategory === 'all' || cardCategory === selectedCategory;

        if (shouldShow) {
          gsap.to(card, {
            opacity: 1,
            scale: 1,
            y: 0,
            duration: 0.5,
            delay: index * 0.05,
            ease: "power2.out",
            display: "block"
          });
        } else {
          gsap.to(card, {
            opacity: 0,
            scale: 0.8,
            y: 20,
            duration: 0.3,
            ease: "power2.in",
            display: "none"
          });
        }
      });
    });
  });


});
</script>