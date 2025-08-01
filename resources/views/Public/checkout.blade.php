@extends('layouts.app')
@section('title', 'Pembayaran & Custom Request')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-credit-card text-indigo-500"></i> Pembayaran & Custom Request</h1>
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-bold mb-4 text-slate-700 flex items-center gap-2"><i class="fa-solid fa-cart-shopping text-indigo-400"></i> Produk di Keranjang</h2>
        <div id="checkoutCartItems" class="mb-4"></div>
        <div class="flex justify-between items-center mb-6">
            <span class="font-bold text-lg">Total:</span>
            <span class="font-bold text-indigo-600 text-lg" id="checkoutCartTotal">Rp 0</span>
        </div>
        <form method="POST" action="{{ route('checkout.submit') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-slate-700 mb-1 font-semibold">Custom Request (Opsional)</label>
                <textarea name="custom_request" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white text-slate-900" placeholder="Deskripsikan permintaan custom Anda di sini..."></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full font-semibold transition flex items-center justify-center gap-2"><i class="fa-solid fa-paper-plane"></i> Proses Pembayaran</button>
        </form>
    </div>
</div>
<script>
    // Render cart dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');
    function renderCheckoutCart() {
        const cartItems = document.getElementById('checkoutCartItems');
        const cartTotal = document.getElementById('checkoutCartTotal');
        let html = '';
        let total = 0;
        for (const id in cart) {
            const item = cart[id];
            html += `<div class='flex justify-between items-center mb-2'>
                <span>${item.nama_produk} <span class='text-xs text-slate-400'>x${item.qty}</span></span>
                <span>Rp ${item.harga.toLocaleString('id-ID')}</span>
            </div>`;
            total += item.harga * item.qty;
        }
        cartItems.innerHTML = html || '<p class="text-slate-400">Keranjang kosong.</p>';
        cartTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
    renderCheckoutCart();
</script>
@endsection
