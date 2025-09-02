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

<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg flex flex-col md:flex-row gap-8 p-8">
        <img src="{{ $this->produk->foto ? Storage::url($this->produk->foto) : '' }}" alt="{{ $this->produk->nama_produk }}" class="w-full md:w-80 h-64 object-cover rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700">
        <div class="flex-1 flex flex-col gap-4">
            <span class="text-xs bg-slate-200/60 dark:bg-slate-700/60 text-indigo-600 dark:text-indigo-300 px-2 py-1 rounded-full w-fit flex items-center gap-1">
                <i class="fa-solid fa-gem"></i> {{ $this->produk->kategori }}
            </span>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $this->produk->nama_produk }}</h1>
            <p class="text-indigo-600 dark:text-indigo-300 font-semibold text-xl">Rp {{ number_format($this->produk->harga,0,',','.') }}</p>
            <p class="text-slate-500 dark:text-slate-300 text-sm">Stok: {{ $this->produk->stok }}</p>
            <p class="text-slate-600 dark:text-slate-300">{{ $this->produk->deskripsi }}</p>
            <button
                id="btnAddDetail"
                class="mt-4 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-full font-semibold transition flex items-center gap-2 w-fit"
                data-id="{{ $this->produk->id }}"
                data-nama="{{ e($this->produk->nama_produk) }}"
                data-kategori="{{ e($this->produk->kategori) }}"
                data-harga="{{ (float) $this->produk->harga }}"
                data-stok="{{ (int) $this->produk->stok }}"
                data-foto="{{ $this->produk->foto ? e(Storage::url($this->produk->foto)) : '' }}"
            >
                <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
            </button>
            <a href="{{ url()->previous() }}" class="mt-2 text-indigo-500 hover:underline flex items-center gap-1"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
</div>
    <div class="mt-6">
        <a href="{{ route('produk') }}" class="inline-flex items-center text-sm text-slate-600 dark:text-slate-300 hover:text-indigo-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar produk
        </a>
    </div>
</div>

<script>
function getCart(){
  try { return JSON.parse(localStorage.getItem('cart') || '{}'); } catch(e){ return {}; }
}
function setCart(cart){ localStorage.setItem('cart', JSON.stringify(cart)); }
function updateCartCount(){ let c=getCart(),n=0; for(const id in c) n+=c[id].qty||0; const el=document.getElementById('cartCount'); if(el) el.innerText=n; }

document.addEventListener('DOMContentLoaded', function(){
  updateCartCount();
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
      // Feedback dan opsi menuju keranjang
      this.disabled = true;
      const old = this.innerHTML;
      this.innerHTML = '<i class="fa-solid fa-check"></i> Ditambahkan';
      setTimeout(() => { this.disabled = false; this.innerHTML = old; }, 1200);
    });
  }
});
</script>
