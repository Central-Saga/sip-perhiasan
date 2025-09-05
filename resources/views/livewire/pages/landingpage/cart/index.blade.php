<?php
use function Livewire\Volt\layout;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

$updateQty = function($id, $delta) {
    $item = Keranjang::find($id);
    if (!$item) return;

    $newQty = $item->jumlah + $delta;
    if ($newQty <= 0) {
        $item->delete();
    } else {
        // Cek stok produk
        if ($newQty > $item->produk->stok) {
            session()->flash('error', 'Jumlah yang diminta melebihi stok yang tersedia. Stok tersisa: ' . $item->produk->stok);
            return;
        }

        $item->update([
            'jumlah' => $newQty,
            'subtotal' => $newQty * $item->harga_satuan
        ]);
    }

    $this->dispatch('cart-updated');
    // Refresh halaman untuk update cart count di header
    $this->redirect(route('cart'), navigate: true);
};

$removeItem = function($id) {
    $item = Keranjang::find($id);
    if ($item) {
        $item->delete();
        $this->dispatch('cart-updated');
        // Refresh halaman untuk update cart count di header
        $this->redirect(route('cart'), navigate: true);
    }
};
?>

<div class="max-w-4xl mx-auto px-4 py-13">
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-cart-shopping text-indigo-500"></i> Keranjang Belanja
    </h1>

    @if (session()->has('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-bold mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <i class="fa-solid fa-cart-plus text-indigo-400"></i> Produk di Keranjang
        </h2>

        @php
        $keranjangItems = collect();
        $customRequest = null;
        $total = 0;

        // Ambil data keranjang dari database
        if (Auth::check()) {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if ($pelanggan) {
        $keranjangItems = Keranjang::with(['produk', 'customRequest'])
        ->where('pelanggan_id', $pelanggan->id)
        ->get();

        // Hitung total
        foreach ($keranjangItems as $item) {
        if ($item->produk_id) {
        $total += $item->subtotal ?? ($item->harga_satuan * $item->jumlah);
        }
        }

        // Ambil custom request jika ada
        $customRequest = $keranjangItems->where('custom_request_id', '!=', null)->first()?->customRequest;
        }
        }
        @endphp

        @if($keranjangItems->count() > 0)
        <div class="mb-6 divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($keranjangItems as $item)
            @if($item->produk)
            <div class="py-4">
                <div class="flex gap-4">
                    <div class="w-24 h-24 md:w-28 md:h-28 flex-shrink-0">
                        @if($item->produk->foto)
                        <img src="{{ Storage::url($item->produk->foto) }}" alt="{{ $item->produk->nama_produk }}"
                            class="w-full h-full object-cover rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700" />
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-gem text-2xl text-slate-400 dark:text-slate-500"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-slate-800 dark:text-slate-100">{{
                                    $item->produk->nama_produk }}</h4>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Kategori: {{ $item->produk->kategori ?? '-' }}
                                </div>
                            </div>
                            <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-red-500"
                                title="Hapus" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <div class="flex items-center gap-2">
                                <button wire:click="updateQty({{ $item->id }}, -1)"
                                    class="w-7 h-7 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-full">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $item->jumlah
                                    }}</span>
                                <button wire:click="updateQty({{ $item->id }}, 1)"
                                    class="w-7 h-7 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-full">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                            <div class="text-indigo-600 dark:text-indigo-300 font-semibold">
                                {{ formatRupiah($item->subtotal ?? ($item->harga_satuan * $item->jumlah)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <i class="fa-solid fa-cart-shopping text-slate-300 dark:text-slate-600 text-5xl mb-3"></i>
            <p class="text-slate-500 dark:text-slate-400">Keranjang belanja Anda kosong.</p>
        </div>
        @endif

        @if($customRequest)
        <div class="mb-6">
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles text-indigo-400"></i> Custom Request
            </h3>
            <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/30">
                <div class="flex items-start gap-4">
                    <div
                        class="w-20 h-20 flex-shrink-0 rounded-md overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                        @if($customRequest->gambar_referensi)
                        <img src="{{ $customRequest->gambar_referensi }}" class="w-full h-full object-cover" />
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-slate-800 dark:text-slate-100">Detail Kustom</div>
                            <span
                                class="text-xs px-2 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                {{ $customRequest->status ?? 'pending' }}
                            </span>
                        </div>
                        <dl
                            class="mt-2 text-sm text-slate-600 dark:text-slate-300 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                            <div>
                                <dt class="inline text-slate-500">Kategori:</dt>
                                <dd class="inline">{{ $customRequest->kategori ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="inline text-slate-500">Material:</dt>
                                <dd class="inline">{{ $customRequest->material ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="inline text-slate-500">Ukuran:</dt>
                                <dd class="inline">{{ $customRequest->ukuran ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="inline text-slate-500">Berat:</dt>
                                <dd class="inline">{{ $customRequest->berat ?? 0 }} gram</dd>
                            </div>
                        </dl>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $customRequest->deskripsi ??
                            '' }}
                        </p>
                        <a href="{{ route('custom.detail') }}"
                            class="mt-3 inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-300 text-sm hover:underline">
                            <i class="fa-solid fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                    Tidak ada harga untuk custom request pada tahap ini.
                </p>
            </div>
        </div>
        @endif

        <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-4 mb-6">
            <span class="font-bold text-lg text-slate-800 dark:text-slate-100">Total:</span>
            <span class="font-bold text-indigo-600 dark:text-indigo-300 text-lg">{{ formatRupiah($total)
                }}</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('produk') }}"
                class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
            </a>
            @if($keranjangItems->count() > 0)
            <a href="{{ route('checkout') }}"
                class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-credit-card"></i> Checkout
            </a>
            @endif
        </div>
    </div>
</div>

<script>
    // Update cart count when cart page loads
document.addEventListener('DOMContentLoaded', function() {
    if (window.updateCartCount) {
        updateCartCount();
    }
});

// Also update when Livewire navigates to this page
document.addEventListener('livewire:navigated', function() {
    if (window.updateCartCount) {
        setTimeout(() => {
            updateCartCount();
        }, 100);
    }
});
</script>