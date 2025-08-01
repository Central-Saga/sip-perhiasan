@extends('layouts.app')
@section('title', 'Transaksi Saya')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
<h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-8 flex items-center gap-2"><i class="fa-solid fa-cart-shopping text-indigo-500"></i> Transaksi Saya</h1>
    <div class="bg-white rounded-xl shadow p-6 mb-8">
<h2 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-indigo-400"></i> Riwayat Transaksi</h2>
        @if(count($transaksis) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="py-2 px-4">Kode</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Total</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $trx)
                        <tr class="border-b">
                            <td class="py-2 px-4 font-mono">{{ $trx->kode_transaksi }}</td>
                            <td class="py-2 px-4">{{ $trx->tanggal_transaksi->format('d M Y') }}</td>
                            <td class="py-2 px-4 text-indigo-600 font-bold">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                            <td class="py-2 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{ $trx->status }}</span>
                            </td>
                            <td class="py-2 px-4">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="text-indigo-500 hover:underline"><i class="fa-solid fa-eye"></i> Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div id="cartSection">
                <h3 class="text-lg font-bold mb-2 text-slate-700 flex items-center gap-2"><i class="fa-solid fa-cart-plus text-indigo-400"></i> Produk di Keranjang</h3>
                <div id="cartItems" class="mb-4"></div>
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-lg">Total:</span>
                    <span class="font-bold text-indigo-600 text-lg" id="cartTotal">Rp 0</span>
                </div>
                <button id="checkoutBtn" class="w-full px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full font-semibold transition flex items-center justify-center gap-2"><i class="fa-solid fa-credit-card"></i> Checkout</button>
            </div>
            <script>
                // Ambil cart dari localStorage
                let cart = JSON.parse(localStorage.getItem('cart') || '{}');
                let produkList = [];
                // Dummy produkList, bisa diisi dari backend jika ingin
                // produkList = [...];
                function renderCart() {
                    const cartItems = document.getElementById('cartItems');
                    const cartTotal = document.getElementById('cartTotal');
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
                renderCart();
                document.getElementById('checkoutBtn').onclick = function() {
                    window.location.href = "{{ route('checkout') }}";
                };
            </script>
        @endif
    </div>
</div>
@endsection
