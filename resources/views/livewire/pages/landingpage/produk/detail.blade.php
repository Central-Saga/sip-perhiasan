<?php
use function Livewire\Volt\{ layout, state, mount };
use App\Models\Produk;

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
        <img src="{{ $this->produk->foto }}" alt="{{ $this->produk->nama_produk }}" class="w-full md:w-80 h-64 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
        <div class="flex-1 flex flex-col gap-4">
            <span class="text-xs bg-slate-200/60 dark:bg-slate-700/60 text-indigo-600 dark:text-indigo-300 px-2 py-1 rounded-full w-fit flex items-center gap-1">
                <i class="fa-solid fa-gem"></i> {{ $this->produk->kategori }}
            </span>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $this->produk->nama_produk }}</h1>
            <p class="text-indigo-600 dark:text-indigo-300 font-semibold text-xl">Rp {{ number_format($this->produk->harga,0,',','.') }}</p>
            <p class="text-slate-500 dark:text-slate-300 text-sm">Stok: {{ $this->produk->stok }}</p>
            <p class="text-slate-600 dark:text-slate-300">{{ $this->produk->deskripsi }}</p>
            <a href="{{ route('cart') }}" class="mt-4 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-full font-semibold transition flex items-center gap-2 w-fit"><i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang</a>
            <a href="{{ url()->previous() }}" class="mt-2 text-indigo-500 hover:underline flex items-center gap-1"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="mt-6">
        <a href="{{ route('produk') }}" class="inline-flex items-center text-sm text-slate-600 dark:text-slate-300 hover:text-indigo-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar produk
        </a>
    </div>
</div>

