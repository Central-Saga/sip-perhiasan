<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

state([
    'produkList' => [],
]);

mount(function () {
    $this->produkList = Produk::where('status', true)->get();
});
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-xl md:text-2xl font-bold text-slate-700 dark:text-slate-100 mb-6 flex items-center gap-2"><i class="fa-solid fa-ring text-indigo-500 dark:text-indigo-300"></i> Semua Produk</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($this->produkList as $produk)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:scale-105 transition-transform border border-slate-100 dark:border-slate-700">
            <img src="{{ $produk->foto ? Storage::url($produk->foto) : '' }}" alt="{{ $produk->nama_produk }}" class="w-full h-48 object-cover bg-slate-100 dark:bg-slate-700">
            <div class="p-5 flex flex-col gap-2">
                <span class="text-xs bg-slate-200/60 dark:bg-slate-700/60 text-indigo-600 dark:text-indigo-300 px-2 py-1 rounded-full w-fit flex items-center gap-1">
                    <i class="fa-solid fa-gem"></i> {{ $produk->kategori }}
                </span>
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $produk->nama_produk }}</h3>
                <p class="text-indigo-700 dark:text-indigo-300 font-medium text-base">Rp {{ number_format($produk->harga,0,',','.') }}</p>
                <p class="text-slate-500 dark:text-slate-300 text-xs">Stok: {{ $produk->stok }}</p>
                <div class="mt-2 flex gap-2">
                    <a href="{{ route('produk.detail', $produk->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-full font-medium transition flex items-center gap-2 text-sm w-fit"><i class="fa-solid fa-eye"></i> Detail</a>
                    <button
                        class="btn-add-cart px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full font-medium transition flex items-center gap-2 text-sm"
                        data-id="{{ $produk->id }}"
                        data-nama="{{ e($produk->nama_produk) }}"
                        data-kategori="{{ e($produk->kategori) }}"
                        data-harga="{{ (float) $produk->harga }}"
                        data-stok="{{ (int) $produk->stok }}"
                        data-foto="{{ $produk->foto ? e(Storage::url($produk->foto)) : '' }}"
                    >
                        <i class="fa-solid fa-cart-plus"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
// Util format
function rupiah(n){ try { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); } catch(e){ return 'Rp 0'; } }

function getCart(){
  try { return JSON.parse(localStorage.getItem('cart') || '{}'); } catch(e){ return {}; }
}

function setCart(cart){
  localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartCount(){
  const cart = getCart();
  let count = 0; for(const id in cart) count += cart[id].qty || 0;
  const el = document.getElementById('cartCount');
  if(el) el.innerText = count;
}

function addToCart(prod){
  const cart = getCart();
  const id = String(prod.id);
  if(!cart[id]){
    cart[id] = {
      id: prod.id,
      nama_produk: prod.nama,
      kategori: prod.kategori,
      harga: Number(prod.harga) || 0,
      stok: Number(prod.stok) || 0,
      foto: prod.foto || '',
      qty: 0,
    };
  }
  if(cart[id].qty < (cart[id].stok || 0)){
    cart[id].qty += 1;
  }
  setCart(cart);
  updateCartCount();
}

document.addEventListener('DOMContentLoaded', function(){
  updateCartCount();
  document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function(){
      const prod = {
        id: this.dataset.id,
        nama: this.dataset.nama,
        kategori: this.dataset.kategori,
        harga: this.dataset.harga,
        stok: this.dataset.stok,
        foto: this.dataset.foto,
      };
      addToCart(prod);
      // Feedback sederhana
      this.disabled = true;
      const old = this.innerHTML;
      this.innerHTML = '<i class="fa-solid fa-check"></i> Ditambahkan';
      setTimeout(() => { this.disabled = false; this.innerHTML = old; }, 1200);
    });
  });
});
</script>
