<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

state([
    'produk' => null,
]);

mount(function ($id) {
    $this->produk = Produk::findOrFail($id);
});
?>

<div>
    <!-- Hero Section -->
    <section
        class="relative min-h-[60vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="detail-bg-1 absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="detail-bg-2 absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl">
            </div>
            <div
                class="detail-bg-3 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl">
            </div>
        </div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="detail-particle absolute w-2 h-2 bg-white/30 rounded-full"
                style="top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="detail-particle absolute w-1 h-1 bg-purple-300/40 rounded-full"
                style="top: 60%; left: 20%; animation-delay: 1s;"></div>
            <div class="detail-particle absolute w-3 h-3 bg-indigo-300/30 rounded-full"
                style="top: 30%; right: 15%; animation-delay: 2s;"></div>
            <div class="detail-particle absolute w-2 h-2 bg-pink-300/40 rounded-full"
                style="bottom: 30%; left: 30%; animation-delay: 3s;"></div>
            <div class="detail-particle absolute w-1 h-1 bg-white/20 rounded-full"
                style="bottom: 20%; right: 25%; animation-delay: 4s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 text-center">
            <!-- Badge -->
            <div
                class="detail-hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                <i class="fa-solid fa-gem text-purple-300"></i>
                <span>{{ $this->produk->kategori }}</span>
            </div>

            <!-- Main Heading -->
            <div class="space-y-4">
                <h1 class="detail-hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                    <span class="block">{{ $this->produk->nama_produk }}</span>
                    <span
                        class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                        Premium
                    </span>
                    <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                        Bliss Silversmith
                    </span>
                </h1>
            </div>

            <!-- Description -->
            <p class="detail-hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                {{ $this->produk->deskripsi }}
            </p>

            <!-- Price -->
            <div class="detail-hero-price mt-8">
                <span class="text-3xl md:text-4xl font-bold text-white">Rp {{
                    number_format($this->produk->harga,0,',','.') }}</span>
                <p class="text-white/60 mt-2">Stok: {{ $this->produk->stok }} tersedia</p>
            </div>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <!-- Product Card -->
            <div class="detail-product-card group relative max-w-5xl mx-auto">
                <div
                    class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-3xl p-8 md:p-12 shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2">

                    <!-- Product Image and Info Grid -->
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <!-- Product Image -->
                        <div class="relative">
                            <div class="detail-image-container relative group">
                                <!-- Glow Effect -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10">
                                </div>

                                <div
                                    class="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-2xl overflow-hidden shadow-xl">
                                    @if($this->produk->foto)
                                    <img src="{{ Storage::url($this->produk->foto) }}"
                                        alt="{{ $this->produk->nama_produk }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-gem text-8xl text-slate-400 dark:text-slate-500"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Floating Elements -->
                                <div
                                    class="detail-floating-1 absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-500 rounded-xl shadow-xl transform rotate-12 hover:rotate-0 transition-transform duration-300">
                                </div>
                                <div
                                    class="detail-floating-2 absolute -bottom-4 -left-4 w-18 h-18 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-lg shadow-xl transform -rotate-12 hover:rotate-0 transition-transform duration-300">
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="space-y-8">
                            <!-- Category Badge -->
                            <div
                                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium">
                                <i class="fa-solid fa-gem"></i>
                                <span>{{ $this->produk->kategori }}</span>
                            </div>

                            <!-- Product Title -->
                            <h2 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-slate-100">
                                {{ $this->produk->nama_produk }}
                            </h2>

                            <!-- Price -->
                            <div class="space-y-2">
                                <p
                                    class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                                    Rp {{ number_format($this->produk->harga,0,',','.') }}
                                </p>
                                <p class="text-slate-500 dark:text-slate-300">
                                    <i class="fa-solid fa-box mr-2"></i>
                                    Stok: {{ $this->produk->stok }} tersedia
                                </p>
                            </div>

                            <!-- Description -->
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Deskripsi Produk</h3>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-lg">
                                    {{ $this->produk->deskripsi }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-4">
                                <button id="btnAddDetail"
                                    class="w-full px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3"
                                    data-id="{{ $this->produk->id }}" data-nama="{{ e($this->produk->nama_produk) }}"
                                    data-kategori="{{ e($this->produk->kategori) }}"
                                    data-harga="{{ (float) $this->produk->harga }}"
                                    data-stok="{{ (int) $this->produk->stok }}"
                                    data-foto="{{ $this->produk->foto ? e(Storage::url($this->produk->foto)) : '' }}">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Tambah ke Keranjang</span>
                                </button>

                                <div class="flex gap-4">
                                    <a href="{{ url()->previous() }}"
                                        class="flex-1 px-6 py-3 bg-white/80 dark:bg-slate-700/80 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-full font-semibold transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-arrow-left"></i>
                                        <span>Kembali</span>
                                    </a>
                                    <a href="{{ route('produk') }}"
                                        class="flex-1 px-6 py-3 bg-white/80 dark:bg-slate-700/80 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-full font-semibold transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-600 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-list"></i>
                                        <span>Semua Produk</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Decorative Elements -->
                <div
                    class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="absolute -bottom-2 -left-2 w-4 h-4 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6">
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
    </section>
</div>

<script>
    function getCart(){
  try { return JSON.parse(localStorage.getItem('cart') || '{}'); } catch(e){ return {}; }
}
function setCart(cart){ localStorage.setItem('cart', JSON.stringify(cart)); }
function updateCartCount(){ let c=getCart(),n=0; for(const id in c) n+=c[id].qty||0; const el=document.getElementById('cartCount'); if(el) el.innerText=n; }

document.addEventListener('DOMContentLoaded', function(){
  updateCartCount();

  // Detail Hero Section Animations
  if (typeof gsap !== "undefined") {
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    const detailTl = gsap.timeline();

    // Initial state - hide elements
    gsap.set(
        [
            ".detail-hero-badge",
            ".detail-hero-title",
            ".detail-hero-description",
            ".detail-hero-price",
        ],
        {
            opacity: 0,
            y: 50,
        }
    );

    // Animate elements in sequence
    detailTl
        .to(".detail-hero-badge", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
        })
        .to(
            ".detail-hero-title",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".detail-hero-description",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.6"
        )
        .to(
            ".detail-hero-price",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.4"
        );

    // Background elements animation
    gsap.to(".detail-bg-1", {
        scale: 1.2,
        x: 30,
        y: -20,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".detail-bg-2", {
        scale: 0.9,
        x: -25,
        y: 25,
        duration: 10,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".detail-bg-3", {
        scale: 1.1,
        x: 20,
        y: -15,
        duration: 6,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // Particles animation
    gsap.to(".detail-particle", {
        y: -30,
        opacity: 0.8,
        duration: 3,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
        stagger: 0.5,
    });

    // Product card animation
    gsap.fromTo(
        ".detail-product-card",
        {
            opacity: 0,
            y: 50,
            scale: 0.9,
        },
        {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".detail-product-card",
                start: "top 80%",
                end: "bottom 20%",
                toggleActions: "play none none none",
            },
        }
    );

    // Floating elements animation
    gsap.to(".detail-floating-1", {
        rotation: 360,
        duration: 20,
        ease: "none",
        repeat: -1,
    });

    gsap.to(".detail-floating-2", {
        rotation: -360,
        duration: 25,
        ease: "none",
        repeat: -1,
    });

    // Product card hover effects
    const productCard = document.querySelector(".detail-product-card");
    if (productCard) {
        const floatingElements = productCard.querySelectorAll(
            ".detail-floating-1, .detail-floating-2"
        );

        productCard.addEventListener("mouseenter", () => {
            gsap.to(productCard, {
                y: -10,
                scale: 1.02,
                duration: 0.3,
                ease: "power2.out",
            });

            gsap.to(floatingElements, {
                scale: 1.2,
                duration: 0.3,
                ease: "power2.out",
                stagger: 0.1,
            });
        });

        productCard.addEventListener("mouseleave", () => {
            gsap.to(productCard, {
                y: 0,
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });

            gsap.to(floatingElements, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
                stagger: 0.1,
            });
        });
    }
  }

  const addBtn = document.querySelector('#btnAddDetail');
  if(addBtn){
    addBtn.addEventListener('click', function(){
      const prod = {
        id: this.dataset.id,
        nama: this.dataset.nama,
        kategori: this.dataset.kategori,
        harga: Number(this.dataset.harga) || 0,
        stok: Number(this.dataset.stok) || 0,
        foto: this.dataset.foto || ''
      };
      const cart = getCart();
      const key = String(prod.id);
      if(!cart[key]){
        cart[key] = { id: prod.id, nama_produk: prod.nama, kategori: prod.kategori, harga: prod.harga, stok: prod.stok, foto: prod.foto, qty: 0 };
      }
      if(cart[key].qty < (cart[key].stok || 0)){
        cart[key].qty += 1;
      }
      setCart(cart);
      updateCartCount();

      // Button animation
      if (typeof gsap !== "undefined") {
        gsap.to(this, {
          scale: 0.95,
          duration: 0.1,
          yoyo: true,
          repeat: 1,
          ease: "power2.inOut",
        });
      }

      // Feedback dan opsi menuju keranjang
      this.disabled = true;
      const old = this.innerHTML;
      this.innerHTML = '<i class="fa-solid fa-check"></i> Ditambahkan';
      setTimeout(() => { this.disabled = false; this.innerHTML = old; }, 1200);
    });
  }
});
</script>
