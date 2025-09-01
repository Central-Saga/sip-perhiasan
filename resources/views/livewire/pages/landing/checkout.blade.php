@layout('components.layouts.landing')

<?php
use Livewire\Volt\Component;
new class extends Component {
    // Tambahkan properti dan logic jika perlu
};
?>
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
            <!-- ...form custom request dan pengiriman... -->
        </form>
    </div>
</div>
