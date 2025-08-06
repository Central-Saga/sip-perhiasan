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
                <h3 class="text-lg font-bold mb-4 text-slate-700 flex items-center gap-2"><i class="fa-solid fa-cart-plus text-indigo-400"></i> Produk di Keranjang</h3>
                <div id="cartItems" class="mb-6 divide-y divide-slate-200"></div>
                <div class="flex justify-between items-center mb-4 pt-4 border-t border-slate-200">
                    <span class="font-bold text-lg">Total:</span>
                    <span class="font-bold text-indigo-600 text-lg" id="cartTotal">Rp 0</span>
                </div>
                <button id="checkoutBtn" class="w-full px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2"><i class="fa-solid fa-credit-card"></i> Checkout</button>
            </div>
            <script>
                // Ambil cart dari localStorage
                let cart = JSON.parse(localStorage.getItem('cart') || '{}');
                let produkList = [];
                
                function renderCart() {
                    const cartItems = document.getElementById('cartItems');
                    const cartTotal = document.getElementById('cartTotal');
                    let html = '';
                    let total = 0;
                    
                    // Jika cart kosong
                    if (Object.keys(cart).length === 0) {
                        cartItems.innerHTML = '<div class="text-center py-6"><i class="fa-solid fa-cart-shopping text-slate-300 text-5xl mb-3"></i><p class="text-slate-400">Keranjang belanja Anda kosong.</p></div>';
                        cartTotal.innerText = 'Rp 0';
                        return;
                    }
                    
                    // Render setiap item dalam cart
                    for (const id in cart) {
                        const item = cart[id];
                        const itemTotal = item.harga * item.qty;
                        total += itemTotal;
                        
                        html += `
                        <div class='py-4'>
                            <div class='flex gap-4'>
                                <div class="w-20 h-20 flex-shrink-0">
                                    <img src="${item.foto || 'https://via.placeholder.com/80'}" alt="${item.nama_produk}" class="w-full h-full object-cover rounded-lg border border-slate-200">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-semibold text-slate-800">${item.nama_produk}</h4>
                                        <button onclick="removeItem(${id})" class="text-slate-400 hover:text-red-500" title="Hapus">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">Kategori: ${item.kategori}</div>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center gap-2">
                                            <button onclick="updateQty(${id}, -1)" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 rounded-full">
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>
                                            <span class="text-sm font-medium">${item.qty}</span>
                                            <button onclick="updateQty(${id}, 1)" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-slate-200 rounded-full">
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="text-indigo-600 font-semibold">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                    
                    cartItems.innerHTML = html;
                    cartTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
                }
                
                function updateQty(id, delta) {
                    if (!cart[id]) return;
                    
                    cart[id].qty += delta;
                    if (cart[id].qty <= 0) {
                        delete cart[id];
                    }
                    
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                    renderCart();
                }
                
                function removeItem(id) {
                    if (confirm('Hapus produk ini dari keranjang?')) {
                        delete cart[id];
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartCount();
                        renderCart();
                    }
                }
                
                function updateCartCount() {
                    let count = 0;
                    for (const id in cart) count += cart[id].qty;
                    const cartCount = document.getElementById('cartCount');
                    if(cartCount) cartCount.innerText = count;
                }
                
                document.getElementById('checkoutBtn').onclick = function() {
                    // Simpan custom request details
                    const customRequest = {
                        deskripsi: document.getElementById('customDesc').value.trim(),
                        material: document.getElementById('customMaterial').value,
                        ukuran: document.getElementById('customSize').value.trim(),
                        referensi: document.getElementById('customImageUrl').value.trim()
                    };
                    
                    // Hanya simpan jika ada data
                    if (customRequest.deskripsi || customRequest.material || customRequest.ukuran || customRequest.referensi) {
                        localStorage.setItem('customRequest', JSON.stringify(customRequest));
                    }
                    
                    window.location.href = "{{ route('checkout') }}";
                };
                
                // Inisialisasi cart
                renderCart();
                updateCartCount();
                
                // Isi form custom request dengan data sebelumnya jika ada
                const savedCustomRequest = JSON.parse(localStorage.getItem('customRequest') || '{}');
                if (savedCustomRequest) {
                    document.getElementById('customDesc').value = savedCustomRequest.deskripsi || '';
                    document.getElementById('customMaterial').value = savedCustomRequest.material || '';
                    document.getElementById('customSize').value = savedCustomRequest.ukuran || '';
                    document.getElementById('customImageUrl').value = savedCustomRequest.referensi || '';
                }
            </script>
        @endif
    </div>
</div>
@endsection
