<?php
use function Livewire\Volt\layout;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.landing');

// Redirect ke login jika belum login
if (!Auth::check()) {
    return redirect()->route('login');
}

?>

@php
// Ambil data keranjang dari database
$user = Auth::user();
$pelanggan = Pelanggan::where('user_id', $user->id)->first();

$keranjangItems = collect();
$customRequest = null;
$total = 0;

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
@endphp

<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-credit-card text-indigo-500"></i> Checkout
    </h1>

    @if (session()->has('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('success'))
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-bold mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-indigo-400"></i> Produk di Keranjang
        </h2>

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
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $item->jumlah }} x {{ formatRupiah($item->harga_satuan) }}
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

        @if($keranjangItems->count() > 0)
        <!-- Form Checkout -->
        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Data Pelanggan -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fa-solid fa-user-circle text-2xl"></i>
                        Data Pelanggan
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fa-solid fa-user text-indigo-500 mr-2"></i>Nama Lengkap
                                </label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->name }}"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 cursor-not-allowed font-medium"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fa-solid fa-lock text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fa-solid fa-envelope text-indigo-500 mr-2"></i>Email
                                </label>
                                <div class="relative">
                                    <input type="email" value="{{ $user->email }}"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 cursor-not-allowed font-medium"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fa-solid fa-lock text-slate-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fa-solid fa-phone text-indigo-500 mr-2"></i>No. Telepon
                                </label>
                                <div class="relative">
                                    <input type="tel" value="{{ $pelanggan->no_telepon ?? 'Belum diisi' }}"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 cursor-not-allowed font-medium"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fa-solid fa-lock text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fa-solid fa-map-marker-alt text-indigo-500 mr-2"></i>Alamat Lengkap
                                </label>
                                <div class="relative">
                                    <textarea rows="3"
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 cursor-not-allowed resize-none font-medium"
                                        readonly>{{ $pelanggan->alamat ?? 'Belum diisi' }}</textarea>
                                    <div class="absolute top-3 right-3">
                                        <i class="fa-solid fa-lock text-slate-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-info-circle text-blue-500 text-lg"></i>
                            </div>
                            <div class="text-sm">
                                <p class="font-semibold text-blue-800 dark:text-blue-200 mb-1">Data Pelanggan</p>
                                <p class="text-blue-700 dark:text-blue-300">Data diambil dari profil Anda. Untuk
                                    mengubah data, silakan perbarui profil di halaman pengaturan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fa-solid fa-credit-card text-2xl"></i>
                        Metode Pembayaran
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="group relative cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="cash" class="sr-only" checked>
                            <div
                                class="p-6 border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl transition-all duration-200 group-hover:shadow-lg">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center">
                                            <i class="fa-solid fa-money-bill-wave text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200">Cash</h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Bayar langsung saat
                                            barang diterima</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-6 h-6 rounded-full border-2 border-emerald-500 bg-emerald-500 flex items-center justify-center">
                                            <i class="fa-solid fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="group relative cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="transfer" class="sr-only">
                            <div
                                class="p-6 border-2 border-slate-200 dark:border-slate-600 rounded-2xl transition-all duration-200 group-hover:border-blue-300 dark:group-hover:border-blue-500 group-hover:shadow-lg bg-white dark:bg-slate-700">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-600 flex items-center justify-center">
                                            <i class="fa-solid fa-university text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200">Transfer Bank
                                        </h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Transfer ke rekening
                                            yang tersedia</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tipe Pesanan -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fa-solid fa-tag text-2xl"></i>
                        Tipe Pesanan
                    </h3>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <label
                            class="flex items-center p-4 border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="biasa" class="mr-3" checked>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-shopping-bag text-indigo-500 text-xl"></i>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-slate-200">Pesanan Biasa</div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">Produk yang sudah tersedia
                                    </div>
                                </div>
                            </div>
                        </label>

                        @if($customRequest)
                        <label
                            class="flex items-center p-4 border-2 border-slate-200 dark:border-slate-600 rounded-xl cursor-pointer hover:border-purple-300 dark:hover:border-purple-500">
                            <input type="radio" name="tipe_pesanan" value="custom" class="mr-3">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-wand-magic-sparkles text-purple-500 text-xl"></i>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-slate-200">Pesanan Custom</div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">Produk sesuai permintaan
                                        khusus</div>
                                </div>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('produk') }}"
                    class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
                </a>

                <button type="submit"
                    class="flex-1 sm:flex-none sm:px-8 sm:py-3 px-4 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    Proses Checkout
                </button>
            </div>
        </form>
        @else
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('produk') }}"
                class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
        @endif
    </div>
</div>