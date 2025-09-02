<?php
use function Livewire\Volt\layout;
layout('components.layouts.landing');
?>

<div class="max-w-4xl mx-auto px-4 py-13">
  <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
    <i class="fa-solid fa-cart-shopping text-indigo-500"></i> Keranjang Belanja
  </h1>

  <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
    <h2 class="text-lg font-bold mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2">
      <i class="fa-solid fa-cart-plus text-indigo-400"></i> Produk di Keranjang
    </h2>

    <div id="cartItems" class="mb-6 divide-y divide-slate-200 dark:divide-slate-700"></div>

    <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-4 mb-6">
      <span class="font-bold text-lg text-slate-800 dark:text-slate-100">Total:</span>
      <span class="font-bold text-indigo-600 dark:text-indigo-300 text-lg" id="cartTotal">Rp 0</span>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('produk') }}" class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
      </a>
      <button id="checkoutBtn" class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-credit-card"></i> Checkout
      </button>
    </div>
  </div>
</div>

<script>
  // Sumber data keranjang: localStorage 'cart'
  let cart = JSON.parse(localStorage.getItem('cart') || '{}');

  function formatRupiah(num) {
    try { return 'Rp ' + Number(num || 0).toLocaleString('id-ID'); } catch(e) { return 'Rp 0'; }
  }

  function updateCartCount() {
    let count = 0;
    for (const id in cart) count += cart[id].qty;
    const cartCount = document.getElementById('cartCount');
    if (cartCount) cartCount.innerText = count;
  }

  function renderCart() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    if (!cartItems || !cartTotal) return;

    // Kosong
    if (Object.keys(cart).length === 0) {
      cartItems.innerHTML = `<div class="text-center py-8">
        <i class=\"fa-solid fa-cart-shopping text-slate-300 dark:text-slate-600 text-5xl mb-3\"></i>
        <p class=\"text-slate-500 dark:text-slate-400\">Keranjang belanja Anda kosong.</p>
      </div>`;
      cartTotal.innerText = 'Rp 0';
      return;
    }

    let html = '';
    let total = 0;
    for (const id in cart) {
      const item = cart[id];
      const itemTotal = (item.harga || 0) * (item.qty || 0);
      total += itemTotal;

      const foto = item.foto || 'https://via.placeholder.com/160x160?text=Produk';
      const nama = item.nama_produk || 'Produk';
      const kategori = item.kategori || '-';

      html += `
        <div class="py-4">
          <div class="flex gap-4">
            <div class="w-24 h-24 md:w-28 md:h-28 flex-shrink-0">
              <img src="${foto}" alt="${nama}" class="w-full h-full object-cover rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700" />
            </div>
            <div class="flex-grow">
              <div class="flex justify-between items-start">
                <div>
                  <h4 class="font-semibold text-slate-800 dark:text-slate-100">${nama}</h4>
                  <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kategori: ${kategori}</div>
                </div>
                <button onclick="removeItem(${id})" class="text-slate-400 hover:text-red-500" title="Hapus">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
              <div class="flex justify-between items-center mt-3">
                <div class="flex items-center gap-2">
                  <button onclick="updateQty(${id}, -1)" class="w-7 h-7 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-full">
                    <i class="fa-solid fa-minus text-[10px]"></i>
                  </button>
                  <span class="text-sm font-medium text-slate-800 dark:text-slate-100">${item.qty}</span>
                  <button onclick="updateQty(${id}, 1)" class="w-7 h-7 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-full">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                  </button>
                </div>
                <div class="text-indigo-600 dark:text-indigo-300 font-semibold">${formatRupiah(itemTotal)}</div>
              </div>
            </div>
          </div>
        </div>`;
    }

    cartItems.innerHTML = html;
    cartTotal.innerText = formatRupiah(total);
  }

  function updateQty(id, delta) {
    if (!cart[id]) return;
    const newQty = (cart[id].qty || 0) + delta;
    if (newQty <= 0) {
      delete cart[id];
    } else {
      cart[id].qty = newQty;
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
  }

  function removeItem(id) {
    if (!cart[id]) return;
    if (confirm('Hapus produk ini dari keranjang?')) {
      delete cart[id];
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartCount();
      renderCart();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Render awal dan sync badge jumlah pada header
    renderCart();
    updateCartCount();

    // Checkout navigasi sederhana ke halaman checkout
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
      checkoutBtn.addEventListener('click', function() {
        window.location.href = "{{ route('checkout') }}";
      });
    }
  });
</script>
