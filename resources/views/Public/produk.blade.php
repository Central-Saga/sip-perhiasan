@extends('layouts.app')
@section('title', 'Semua Produk')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
<h1 class="text-xl md:text-2xl font-bold text-slate-700 mb-6 flex items-center gap-2"><i class="fa-solid fa-ring text-indigo-500"></i> Semua Produk</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($produkList as $produk)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:scale-105 transition-transform border border-slate-100">
            <img src="{{ $produk['foto'] }}" alt="{{ $produk['nama_produk'] }}" class="w-full h-48 object-cover">
            <div class="p-5 flex flex-col gap-2">
                <span class="text-xs bg-slate-200/60 text-indigo-600 px-2 py-1 rounded-full w-fit flex items-center gap-1">
                    <i class="fa-solid fa-gem"></i> {{ $produk['kategori'] }}
                </span>
                <h3 class="text-base font-semibold text-slate-800">{{ $produk['nama_produk'] }}</h3>
                <p class="text-indigo-700 font-medium text-base">Rp {{ number_format($produk['harga'],0,',','.') }}</p>
                <p class="text-slate-500 text-xs">Stok: {{ $produk['stok'] }}</p>
                <button onclick="addToCart({{ $produk['id'] }})" class="mt-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full font-medium transition flex items-center gap-2 text-sm"><i class="fa-solid fa-cart-plus"></i> Tambah ke Cart</button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    // Cart logic (localStorage)
    const produkList = @json($produkList);
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');

    function updateCartCount() {
        let count = 0;
        for (const id in cart) count += cart[id].qty;
        const cartCount = document.getElementById('cartCount');
        if(cartCount) cartCount.innerText = count;
    }

    function addToCart(id) {
        const produk = produkList.find(p => p.id === id);
        if (!produk) return;
        if (!cart[id]) cart[id] = { ...produk, qty: 0 };
        if (cart[id].qty < produk.stok) cart[id].qty++;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        alert('Produk telah ditambahkan ke keranjang!');
    }

    // Inisialisasi cart count saat load
    updateCartCount();
</script>
@endsection
