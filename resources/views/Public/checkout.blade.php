@extends('layouts.app')
@section('title', 'Pembayaran & Custom Request')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-credit-card text-indigo-500"></i> Pembayaran & Custom Request</h1>
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-bold mb-4 text-slate-700 flex items-center gap-2"><i class="fa-solid fa-cart-shopping text-indigo-400"></i> Produk di Keranjang</h2>
        <div id="checkoutCartItems" class="mb-6 divide-y divide-slate-200"></div>
        <div class="flex justify-between items-center border-t border-slate-200 pt-4 mb-6">
            <span class="font-bold text-lg">Total:</span>
            <span class="font-bold text-indigo-600 text-lg" id="checkoutCartTotal">Rp 0</span>
        </div>
        
        <form method="POST" action="{{ route('checkout.submit') }}" class="space-y-6">
            @csrf
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                <h3 class="font-bold text-indigo-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-gem"></i> Detail Custom Request
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-slate-700 mb-1 text-sm font-medium">Deskripsi Kebutuhan</label>
                        <textarea name="deskripsi" id="checkout_deskripsi" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white text-slate-900" placeholder="Jelaskan perhiasan yang Anda inginkan..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 mb-1 text-sm font-medium">Material</label>
                            <select name="material" id="checkout_material" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Pilih Material</option>
                                <option value="Silver">Silver</option>
                                <option value="Gold">Gold</option>
                                <option value="Platinum">Platinum</option>
                                <option value="Rose Gold">Rose Gold</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-slate-700 mb-1 text-sm font-medium">Ukuran</label>
                            <input type="text" name="ukuran" id="checkout_ukuran" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white text-slate-900" placeholder="Contoh: Cincin ukuran 7">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-slate-700 mb-1 text-sm font-medium">Referensi (URL Gambar)</label>
                        <input type="text" name="referensi" id="checkout_referensi" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white text-slate-900" placeholder="https://example.com/image.jpg">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_custom_only" id="is_custom_only" class="mr-2 h-4 w-4 text-indigo-600 rounded">
                        <label for="is_custom_only" class="text-sm text-slate-700">Saya hanya ingin membuat custom request (tanpa membeli produk dari katalog)</label>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="font-bold text-slate-700 mb-4">Data Pengiriman</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 mb-1 text-sm font-medium">Nama Lengkap</label>
                            <input type="text" name="nama" class="w-full px-3 py-2 border border-slate-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-slate-700 mb-1 text-sm font-medium">Nomor Telepon</label>
                            <input type="text" name="telepon" class="w-full px-3 py-2 border border-slate-300 rounded-md" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-slate-700 mb-1 text-sm font-medium">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-md" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-slate-700 mb-1 text-sm font-medium">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full px-5 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2"><i class="fa-solid fa-paper-plane"></i> Proses Pembayaran</button>
        </form>
    </div>
</div>
<script>
    // Render cart dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');
    let customRequest = JSON.parse(localStorage.getItem('customRequest') || '{}');
    
    function renderCheckoutCart() {
        const cartItems = document.getElementById('checkoutCartItems');
        const cartTotal = document.getElementById('checkoutCartTotal');
        let html = '';
        let total = 0;
        
        // Jika cart kosong
        if (Object.keys(cart).length === 0) {
            cartItems.innerHTML = '<div class="text-center py-6"><i class="fa-solid fa-cart-shopping text-slate-300 text-5xl mb-3"></i><p class="text-slate-400">Keranjang belanja Anda kosong.</p></div>';
            cartTotal.innerText = 'Rp 0';
            return;
        }
        
        // Render setiap item dalam cart dengan gambar produk
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
                        <h4 class="font-semibold text-slate-800">${item.nama_produk}</h4>
                        <div class="text-xs text-slate-500 mt-1">Kategori: ${item.kategori}</div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-slate-600">Jumlah: ${item.qty}</span>
                            <div class="text-indigo-600 font-semibold">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        
        cartItems.innerHTML = html;
        cartTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Isi form custom request dengan data dari localStorage
    function populateCustomRequestForm() {
        if (customRequest) {
            document.getElementById('checkout_deskripsi').value = customRequest.deskripsi || '';
            document.getElementById('checkout_material').value = customRequest.material || '';
            document.getElementById('checkout_ukuran').value = customRequest.ukuran || '';
            document.getElementById('checkout_referensi').value = customRequest.referensi || '';
        }
    }
    
    // Ketika submit form, simpan cart dan custom request di hidden field
    document.querySelector('form').addEventListener('submit', function(e) {
        const hiddenCartField = document.createElement('input');
        hiddenCartField.type = 'hidden';
        hiddenCartField.name = 'cart_data';
        hiddenCartField.value = JSON.stringify(cart);
        this.appendChild(hiddenCartField);
        
        // Setelah submit berhasil, kosongkan cart
        localStorage.removeItem('cart');
        localStorage.removeItem('customRequest');
    });
    
    // Initialize
    renderCheckoutCart();
    populateCustomRequestForm();
</script>
@endsection
