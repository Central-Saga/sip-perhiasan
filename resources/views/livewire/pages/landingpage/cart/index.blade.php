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

    <div id="customRequestSection" class="mb-6 hidden">
      <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-wand-magic-sparkles text-indigo-400"></i> Custom Request
      </h3>
      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/30">
        <div class="flex items-start gap-4">
          <div
            class="w-20 h-20 flex-shrink-0 rounded-md overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800"
            id="crImageWrap"></div>
          <div class="flex-1">
            <div class="flex items-center justify-between">
              <div class="font-semibold text-slate-800 dark:text-slate-100">Detail Kustom</div>
              <span id="crStatus"
                class="text-xs px-2 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">pending</span>
            </div>
            <dl class="mt-2 text-sm text-slate-600 dark:text-slate-300 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
              <div>
                <dt class="inline text-slate-500">Kategori:</dt>
                <dd class="inline" id="crKategori">-</dd>
              </div>
              <div>
                <dt class="inline text-slate-500">Material:</dt>
                <dd class="inline" id="crMaterial">-</dd>
              </div>
              <div>
                <dt class="inline text-slate-500">Ukuran:</dt>
                <dd class="inline" id="crUkuran">-</dd>
              </div>
              <div>
                <dt class="inline text-slate-500">Berat:</dt>
                <dd class="inline" id="crBerat">0 gram</dd>
              </div>
            </dl>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300" id="crDeskripsi"></p>
            <a href="{{ route('custom.detail') }}"
              class="mt-3 inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-300 text-sm hover:underline">
              <i class="fa-solid fa-eye"></i> Lihat Detail
            </a>
          </div>
        </div>
        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Tidak ada harga untuk custom request pada tahap ini.
        </p>
      </div>
    </div>

    <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-4 mb-6">
      <span class="font-bold text-lg text-slate-800 dark:text-slate-100">Total:</span>
      <span class="font-bold text-indigo-600 dark:text-indigo-300 text-lg" id="cartTotal">Rp 0</span>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('produk') }}"
        class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
      </a>
      <button id="checkoutBtn"
        class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-credit-card"></i> Checkout
      </button>
    </div>
  </div>
</div>

<script>
  // Sumber data keranjang: localStorage 'cart' + 'customRequest'
  let cart = JSON.parse(localStorage.getItem('cart') || '{}');
  let customReq = null;

  function formatRupiah(num) {
    try { return 'Rp ' + Number(num || 0).toLocaleString('id-ID'); } catch(e) { return 'Rp 0'; }
  }

  function updateCartCount() {
    // Use CartManager if available, otherwise fallback
    if (window.cartManager) {
      window.cartManager.updateCartCount();
    } else {
      // Fallback implementation
      let count = 0;
      for (const id in cart) count += cart[id].qty;
      const cartCount = document.getElementById('cartCount');
      if (cartCount) cartCount.innerText = count;
    }
  }

  function renderCart() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    if (!cartItems || !cartTotal) return;

    // Custom request section
    try { customReq = JSON.parse(localStorage.getItem('customRequest') || 'null'); } catch(_) { customReq = null; }
    const crSection = document.getElementById('customRequestSection');
    if (customReq) {
      const imgWrap = document.getElementById('crImageWrap');
      imgWrap.innerHTML = customReq.gambar_referensi ? `<img src="${customReq.gambar_referensi}" class="w-full h-full object-cover" />` : `<div class='w-full h-full flex items-center justify-center text-slate-400 text-xs'>No Image</div>`;
      document.getElementById('crKategori').innerText = customReq.kategori || '-';
      document.getElementById('crMaterial').innerText = customReq.material || '-';
      document.getElementById('crUkuran').innerText = customReq.ukuran || '-';
      document.getElementById('crBerat').innerText = (customReq.berat || 0) + ' gram';
      document.getElementById('crDeskripsi').innerText = customReq.deskripsi || '';
      document.getElementById('crStatus').innerText = (customReq.status || 'pending');
      crSection.classList.remove('hidden');
    } else {
      crSection.classList.add('hidden');
    }

    // Kosong (produk) bila tidak ada produk dan tidak ada custom request
    if (Object.keys(cart).length === 0 && !customReq) {
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