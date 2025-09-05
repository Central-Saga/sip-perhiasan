<?php
use function Livewire\Volt\{ layout, state, mount, action };
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

layout('components.layouts.landing');

// Redirect ke login jika belum login
if (!Auth::check()) {
    return redirect()->route('login');
}

state([
    'metode_pembayaran' => 'cash',
    'tipe_pesanan' => 'biasa',
    'bukti_pembayaran' => null,
    'isProcessing' => false,
    'keranjangItems' => collect(),
    'total' => 0,
    'customRequest' => null,
    'user' => null,
    'pelanggan' => null,
]);

mount(function () {
    $this->user = Auth::user();
    $this->pelanggan = Pelanggan::where('user_id', $this->user->id)->first();
    $this->loadCartData();
});

$loadCartData = function () {
    $user = Auth::user();
    $pelanggan = Pelanggan::where('user_id', $user->id)->first();

    if ($pelanggan) {
        $this->keranjangItems = Keranjang::with(['produk', 'customRequest'])
            ->where('pelanggan_id', $pelanggan->id)
            ->get();

        $this->total = $this->keranjangItems->sum(function ($item) {
            return $item->subtotal ?? ($item->harga_satuan * $item->jumlah);
        });

        $this->customRequest = $this->keranjangItems->where('custom_request_id', '!=', null)->first()?->customRequest;
    }
};

$processCheckout = action(function () {
    $this->isProcessing = true;

    try {
        DB::beginTransaction();

        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            session()->flash('error', 'Profil pelanggan tidak ditemukan.');
        } elseif ($this->keranjangItems->isEmpty()) {
            session()->flash('error', 'Keranjang belanja kosong.');
        } else {
            // Generate kode transaksi
        $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Buat transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'kode_transaksi' => $kodeTransaksi,
            'total_harga' => $this->total,
            'status' => 'PENDING',
            'tipe_pesanan' => $this->tipe_pesanan,
        ]);

        // Buat detail transaksi
        foreach ($this->keranjangItems as $item) {
            if ($item->produk) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                    'subtotal' => $item->subtotal ?? ($item->harga_satuan * $item->jumlah),
                ]);

                // Update stok produk
                $produk = Produk::find($item->produk_id);
                if ($produk) {
                    $produk->decrement('stok', $item->jumlah);
                }
            }
        }

        // Handle bukti pembayaran
        $buktiTransferPath = null;
        if ($this->metode_pembayaran === 'transfer' && $this->bukti_pembayaran) {
            $filename = 'bukti_transfer_' . $transaksi->id . '_' . time() . '.' . $this->bukti_pembayaran->getClientOriginalExtension();
            $buktiTransferPath = $this->bukti_pembayaran->storeAs('bukti_pembayaran', $filename, 'public');
        }

        // Buat pembayaran
        $pembayaran = Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'metode' => $this->metode_pembayaran,
            'bukti_transfer' => $buktiTransferPath,
            'status' => $this->metode_pembayaran === 'cash' ? 'DIBAYAR' : 'PENDING',
            'tanggal_bayar' => $this->metode_pembayaran === 'cash' ? now() : null,
        ]);

        // Update status transaksi
        if ($this->metode_pembayaran === 'cash') {
            $transaksi->update(['status' => 'DIBAYAR']);
        }

        // Hapus keranjang setelah checkout
        Keranjang::where('pelanggan_id', $pelanggan->id)->delete();

        DB::commit();

        $message = $this->metode_pembayaran === 'cash'
            ? 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi dari admin.'
            : 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran dan tunggu konfirmasi dari admin.';

        session()->flash('success', $message);

        // Redirect ke halaman transaksi
        $this->redirect(route('transaksi'));
        }

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
    } finally {
        $this->isProcessing = false;
    }
});
?>

<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div
        class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Hero Section -->
    <section
        class="relative min-h-[40vh] py-20 flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="product-bg-1 absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="product-bg-2 absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl">
            </div>
            <div
                class="product-bg-3 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl">
            </div>
        </div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="product-particle absolute w-2 h-2 bg-white/30 rounded-full"
                style="top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="product-particle absolute w-1 h-1 bg-purple-300/40 rounded-full"
                style="top: 60%; left: 20%; animation-delay: 1s;"></div>
            <div class="product-particle absolute w-3 h-3 bg-indigo-300/30 rounded-full"
                style="top: 30%; right: 15%; animation-delay: 2s;"></div>
            <div class="product-particle absolute w-2 h-2 bg-pink-300/40 rounded-full"
                style="bottom: 30%; left: 30%; animation-delay: 3s;"></div>
            <div class="product-particle absolute w-1 h-1 bg-white/20 rounded-full"
                style="bottom: 20%; right: 25%; animation-delay: 4s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 text-center">
            <div class="space-y-8">
                <!-- Badge -->
                <div
                    class="product-hero-badge inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-medium">
                    <i class="fa-solid fa-credit-card text-purple-300"></i>
                    <span>Checkout</span>
                </div>

                <!-- Main Heading -->
                <div class="space-y-4">
                    <h1 class="product-hero-title text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        <span class="block">Proses</span>
                        <span
                            class="block bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                            Checkout
                        </span>
                        <span class="block text-2xl md:text-3xl font-light text-white/80 mt-2">
                            Bliss Silversmith
                        </span>
                    </h1>
                </div>

                <!-- Description -->
                <p class="product-hero-description text-lg md:text-xl text-white/70 leading-relaxed max-w-3xl mx-auto">
                    Selesaikan pembelian Anda dengan mudah dan aman.
                    Pilihan terbaik untuk melengkapi koleksi perhiasan Anda.
                </p>
            </div>
        </div>
    </section>

    <!-- Checkout Section -->
    <section
        class="relative w-full py-32 bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-6xl mx-auto px-6">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-800 rounded-full text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Checkout Process</span>
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-slate-100 mb-6">
                    <span class="block">Selesaikan</span>
                    <span
                        class="block bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                        Pembelian
                    </span>
                </h2>

                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    Lengkapi data dan pilih metode pembayaran untuk menyelesaikan pesanan Anda.
                </p>
            </div>

            <!-- Checkout Content -->
            <div
                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-xl">
                <!-- Cart Summary -->
                <div class="p-8 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-shopping-cart text-indigo-500"></i>
                        Ringkasan Pesanan
                    </h3>

                    @if($this->keranjangItems->count() > 0)
                    <div class="space-y-4">
                        @foreach($this->keranjangItems as $item)
                        @if($item->produk)
                        <div class="flex gap-6 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                            <div class="w-20 h-20 flex-shrink-0">
                                @if($item->produk->foto)
                                <img src="{{ Storage::url($item->produk->foto) }}"
                                    alt="{{ $item->produk->nama_produk }}"
                                    class="w-full h-full object-cover rounded-xl border border-slate-200 dark:border-slate-600" />
                                @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl border-2 border-dashed border-purple-200 dark:border-purple-700">
                                    <i class="fa-solid fa-gem text-2xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-slate-800 dark:text-slate-100 text-lg">{{
                                    $item->produk->nama_produk }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Kategori: {{
                                        $item->produk->kategori ?? '-' }}</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">•</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Qty: {{ $item->jumlah
                                        }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($item->subtotal ?? ($item->harga_satuan * $item->jumlah), 0,
                                    ',', '.') }}
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    @ {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div
                        class="mt-6 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl border border-indigo-200 dark:border-indigo-700">
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-slate-800 dark:text-slate-100">Total Pembayaran</span>
                            <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">Rp {{
                                number_format($this->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $this->keranjangItems->count()
                            }} item
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-shopping-cart text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
                        <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Keranjang Kosong</h4>
                        <p class="text-slate-500 dark:text-slate-400">Tidak ada produk untuk checkout</p>
                    </div>
                    @endif
                </div>

                @if($this->keranjangItems->count() > 0)
                <!-- Checkout Form -->
                <form wire:submit="processCheckout" class="p-8 space-y-8">

                    <!-- Data Pelanggan -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white flex items-center gap-3 mb-6">
                                <i class="fa-solid fa-user-circle text-3xl"></i>
                                Data Pelanggan
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-white/90 mb-2">
                                            <i class="fa-solid fa-user mr-2"></i>Nama Lengkap
                                        </label>
                                        <div class="relative">
                                            <input type="text" value="{{ $this->user->name }}"
                                                class="w-full px-4 py-3 border-2 border-white/20 rounded-xl bg-white/10 text-white placeholder-white/70 cursor-not-allowed font-medium"
                                                readonly>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fa-solid fa-lock text-white/50"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-white/90 mb-2">
                                            <i class="fa-solid fa-envelope mr-2"></i>Email
                                        </label>
                                        <div class="relative">
                                            <input type="email" value="{{ $this->user->email }}"
                                                class="w-full px-4 py-3 border-2 border-white/20 rounded-xl bg-white/10 text-white placeholder-white/70 cursor-not-allowed font-medium"
                                                readonly>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fa-solid fa-lock text-white/50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-white/90 mb-2">
                                            <i class="fa-solid fa-phone mr-2"></i>No. Telepon
                                        </label>
                                        <div class="relative">
                                            <input type="tel"
                                                value="{{ $this->pelanggan->no_telepon ?? 'Belum diisi' }}"
                                                class="w-full px-4 py-3 border-2 border-white/20 rounded-xl bg-white/10 text-white placeholder-white/70 cursor-not-allowed font-medium"
                                                readonly>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fa-solid fa-lock text-white/50"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-white/90 mb-2">
                                            <i class="fa-solid fa-map-marker-alt mr-2"></i>Alamat Lengkap
                                        </label>
                                        <div class="relative">
                                            <textarea rows="3"
                                                class="w-full px-4 py-3 border-2 border-white/20 rounded-xl bg-white/10 text-white placeholder-white/70 cursor-not-allowed resize-none font-medium"
                                                readonly>{{ $this->pelanggan->alamat ?? 'Belum diisi' }}</textarea>
                                            <div class="absolute top-3 right-3">
                                                <i class="fa-solid fa-lock text-white/50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-info-circle text-white/80 text-lg mt-1"></i>
                                    <div class="text-sm">
                                        <p class="font-semibold text-white mb-1">Data Pelanggan</p>
                                        <p class="text-white/80">Data diambil dari profil Anda. Untuk mengubah data,
                                            silakan perbarui profil di halaman pengaturan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white flex items-center gap-3 mb-6">
                                <i class="fa-solid fa-credit-card text-3xl"></i>
                                Metode Pembayaran
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <label class="group relative cursor-pointer">
                                    <input type="radio" wire:model="metode_pembayaran" name="metode_pembayaran"
                                        value="cash" class="sr-only">
                                    <div id="cash-card"
                                        class="p-6 border-2 border-white bg-white/20 backdrop-blur-sm rounded-2xl transition-all duration-300 group-hover:bg-white/30 group-hover:border-white">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-14 h-14 rounded-full bg-white/30 flex items-center justify-center">
                                                    <i class="fa-solid fa-money-bill-wave text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-bold text-white">Cash</h4>
                                                <p class="text-white/80 mt-1">Bayar langsung saat barang diterima</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div id="cash-check"
                                                    class="w-6 h-6 rounded-full border-2 border-white bg-white flex items-center justify-center">
                                                    <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="group relative cursor-pointer">
                                    <input type="radio" wire:model="metode_pembayaran" name="metode_pembayaran"
                                        value="transfer" class="sr-only">
                                    <div id="transfer-card"
                                        class="p-6 border-2 border-white/30 bg-white/10 backdrop-blur-sm rounded-2xl transition-all duration-300 group-hover:bg-white/20 group-hover:border-white/50">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                                                    <i class="fa-solid fa-university text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-bold text-white">Transfer Bank</h4>
                                                <p class="text-white/80 mt-1">Transfer ke rekening yang tersedia</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div id="transfer-check"
                                                    class="w-6 h-6 rounded-full border-2 border-white/50 flex items-center justify-center">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti Pembayaran (untuk transfer) -->
                    <div id="upload-section"
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl overflow-hidden hidden">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white flex items-center gap-3 mb-6">
                                <i class="fa-solid fa-upload text-3xl"></i>
                                Upload Bukti Pembayaran
                            </h3>

                            <div>
                                <label class="block text-sm font-semibold text-white/90 mb-2">
                                    <i class="fa-solid fa-image mr-2"></i>File Bukti Transfer
                                </label>

                                <!-- Drag & Drop Area -->
                                <div id="drop-zone"
                                    class="relative border-2 border-dashed border-white/30 rounded-xl p-8 text-center transition-all duration-300 hover:border-white/50 hover:bg-white/5">
                                    <input type="file" wire:model="bukti_pembayaran" id="bukti_pembayaran"
                                        accept="image/*" class="hidden">

                                    <!-- Default State -->
                                    <div id="drop-zone-content" class="space-y-4">
                                        <div
                                            class="mx-auto w-16 h-16 bg-white/10 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-cloud-upload-alt text-white text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium">Drag & drop file bukti transfer di sini
                                            </p>
                                            <p class="text-white/70 text-sm mt-1">atau klik untuk memilih file</p>
                                        </div>
                                        <div class="text-xs text-white/60">
                                            Format: JPG, PNG, PDF (Max: 5MB)
                                        </div>
                                    </div>

                                    <!-- Preview State -->
                                    <div id="preview-content" class="hidden">
                                        <div class="flex items-center justify-center space-x-4">
                                            <div id="preview-image"
                                                class="w-20 h-20 rounded-lg overflow-hidden border-2 border-white/30">
                                                <img id="preview-img" src="" alt="Preview"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div class="text-left">
                                                <p id="preview-name" class="text-white font-medium"></p>
                                                <p id="preview-size" class="text-white/70 text-sm"></p>
                                                <button type="button" id="remove-file"
                                                    class="text-red-300 hover:text-red-200 text-sm mt-1">
                                                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div id="file-error"
                                    class="hidden mt-2 p-2 bg-red-500/20 border border-red-500/30 rounded-lg">
                                    <p class="text-red-200 text-sm"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipe Pesanan -->
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-white flex items-center gap-3 mb-6">
                                <i class="fa-solid fa-tag text-3xl"></i>
                                Tipe Pesanan
                            </h3>

                            <div class="space-y-4">
                                <label
                                    class="flex items-center p-6 border-2 border-white/30 bg-white/10 backdrop-blur-sm rounded-2xl cursor-pointer hover:bg-white/20 transition-all duration-300">
                                    <input type="radio" wire:model="tipe_pesanan" value="biasa" class="mr-4">
                                    <div class="flex items-center gap-4">
                                        <i class="fa-solid fa-shopping-bag text-white text-2xl"></i>
                                        <div>
                                            <div class="font-bold text-white text-lg">Pesanan Biasa</div>
                                            <div class="text-white/80">Produk yang sudah tersedia</div>
                                        </div>
                                    </div>
                                </label>

                                @if($this->customRequest)
                                <label
                                    class="flex items-center p-6 border-2 border-white/30 bg-white/10 backdrop-blur-sm rounded-2xl cursor-pointer hover:bg-white/20 transition-all duration-300">
                                    <input type="radio" wire:model="tipe_pesanan" value="custom" class="mr-4">
                                    <div class="flex items-center gap-4">
                                        <i class="fa-solid fa-wand-magic-sparkles text-white text-2xl"></i>
                                        <div>
                                            <div class="font-bold text-white text-lg">Pesanan Custom</div>
                                            <div class="text-white/80">Produk sesuai permintaan khusus</div>
                                        </div>
                                    </div>
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('cart') }}"
                            class="flex-1 sm:flex-none sm:px-8 sm:py-4 px-6 py-4 rounded-2xl bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold transition-all duration-300 flex items-center justify-center gap-3 text-lg border border-slate-200 dark:border-slate-600 hover:shadow-lg hover:scale-105">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Keranjang
                        </a>

                        <button type="submit" wire:loading.attr="disabled" wire:target="processCheckout"
                            class="flex-1 sm:flex-none sm:px-8 sm:py-4 px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-2xl font-bold transition-all duration-300 flex items-center justify-center gap-3 text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <div wire:loading.remove wire:target="processCheckout">
                                <i class="fa-solid fa-credit-card"></i>
                                Proses Checkout
                            </div>
                            <div wire:loading wire:target="processCheckout" class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                Memproses...
                            </div>
                        </button>
                    </div>
                </form>
                @else
                <div class="p-8 text-center">
                    <a href="{{ route('produk') }}"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Mulai Belanja</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-16">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </section>
</div>

<script>
    // Auto-hide notifications
    setTimeout(() => {
        const notifications = document.querySelectorAll('.fixed.top-4.right-4');
        notifications.forEach(notification => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        });
    }, 3000);

    // Toggle upload section based on payment method
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="metode_pembayaran"]');
        const uploadSection = document.getElementById('upload-section');
        const fileInput = document.getElementById('bukti_pembayaran');
        const cashCard = document.getElementById('cash-card');
        const transferCard = document.getElementById('transfer-card');
        const cashCheck = document.getElementById('cash-check');
        const transferCheck = document.getElementById('transfer-check');

        // Function to update visual state with GSAP animations
        function updateVisualState(selectedValue) {
            if (selectedValue === 'cash') {
                // Cash selected - Animate cash card selection
                gsap.to(cashCard, {
                    duration: 0.3,
                    scale: 1.02,
                    ease: "power2.out",
                    onComplete: () => {
                        gsap.to(cashCard, { duration: 0.2, scale: 1, ease: "power2.out" });
                    }
                });

                // Animate cash check appearance
                gsap.fromTo(cashCheck,
                    { scale: 0, opacity: 0 },
                    {
                        duration: 0.4,
                        scale: 1,
                        opacity: 1,
                        ease: "back.out(1.7)",
                        onStart: () => {
                            cashCard.classList.remove('border-white/30', 'bg-white/10');
                            cashCard.classList.add('border-white', 'bg-white/20');
                            cashCheck.classList.remove('border-white/50');
                            cashCheck.classList.add('border-white', 'bg-white');
                            cashCheck.innerHTML = '<i class="fa-solid fa-check text-emerald-600 text-xs"></i>';
                        }
                    }
                );

                // Animate transfer card deselection
                gsap.to(transferCard, {
                    duration: 0.3,
                    scale: 0.98,
                    ease: "power2.out",
                    onComplete: () => {
                        gsap.to(transferCard, { duration: 0.2, scale: 1, ease: "power2.out" });
                    }
                });

                // Animate transfer check disappearance
                gsap.to(transferCheck, {
                    duration: 0.2,
                    scale: 0,
                    opacity: 0,
                    ease: "power2.in",
                    onComplete: () => {
                        transferCard.classList.remove('border-white', 'bg-white/20');
                        transferCard.classList.add('border-white/30', 'bg-white/10');
                        transferCheck.classList.remove('border-white', 'bg-white');
                        transferCheck.classList.add('border-white/50');
                        transferCheck.innerHTML = '';
                    }
                });

                // Animate upload section slide out
                gsap.to(uploadSection, {
                    duration: 0.4,
                    height: 0,
                    opacity: 0,
                    y: -20,
                    ease: "power2.inOut",
                    onComplete: () => {
                        uploadSection.classList.add('hidden');
                        fileInput.required = false;
                        fileInput.value = '';
                    }
                });

            } else if (selectedValue === 'transfer') {
                // Transfer selected - Animate transfer card selection
                gsap.to(transferCard, {
                    duration: 0.3,
                    scale: 1.02,
                    ease: "power2.out",
                    onComplete: () => {
                        gsap.to(transferCard, { duration: 0.2, scale: 1, ease: "power2.out" });
                    }
                });

                // Animate transfer check appearance
                gsap.fromTo(transferCheck,
                    { scale: 0, opacity: 0 },
                    {
                        duration: 0.4,
                        scale: 1,
                        opacity: 1,
                        ease: "back.out(1.7)",
                        onStart: () => {
                            transferCard.classList.remove('border-white/30', 'bg-white/10');
                            transferCard.classList.add('border-white', 'bg-white/20');
                            transferCheck.classList.remove('border-white/50');
                            transferCheck.classList.add('border-white', 'bg-white');
                            transferCheck.innerHTML = '<i class="fa-solid fa-check text-emerald-600 text-xs"></i>';
                        }
                    }
                );

                // Animate cash card deselection
                gsap.to(cashCard, {
                    duration: 0.3,
                    scale: 0.98,
                    ease: "power2.out",
                    onComplete: () => {
                        gsap.to(cashCard, { duration: 0.2, scale: 1, ease: "power2.out" });
                    }
                });

                // Animate cash check disappearance
                gsap.to(cashCheck, {
                    duration: 0.2,
                    scale: 0,
                    opacity: 0,
                    ease: "power2.in",
                    onComplete: () => {
                        cashCard.classList.remove('border-white', 'bg-white/20');
                        cashCard.classList.add('border-white/30', 'bg-white/10');
                        cashCheck.classList.remove('border-white', 'bg-white');
                        cashCheck.classList.add('border-white/50');
                        cashCheck.innerHTML = '';
                    }
                });

                // Animate upload section slide in
                uploadSection.classList.remove('hidden');
                gsap.fromTo(uploadSection,
                    { height: 0, opacity: 0, y: -20 },
                    {
                        duration: 0.5,
                        height: "auto",
                        opacity: 1,
                        y: 0,
                        ease: "power2.out",
                        onComplete: () => {
                            fileInput.required = true;
                        }
                    }
                );
            }
        }

        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                updateVisualState(this.value);
            });
        });

        // Initialize animations on page load
        function initializeAnimations() {
            // Set initial state for cards
            gsap.set([cashCard, transferCard], { scale: 1 });
            gsap.set([cashCheck, transferCheck], { scale: 1, opacity: 1 });

            // Animate cards entrance
            gsap.fromTo([cashCard, transferCard],
                { y: 30, opacity: 0 },
                {
                    duration: 0.6,
                    y: 0,
                    opacity: 1,
                    ease: "power2.out",
                    stagger: 0.1
                }
            );

            // Set initial state based on default selection (cash)
            updateVisualState('cash');
        }

        // Add smooth hover effects
        function addHoverEffects() {
            [cashCard, transferCard].forEach(card => {
                card.addEventListener('mouseenter', () => {
                    if (!card.classList.contains('border-white')) {
                        gsap.to(card, {
                            duration: 0.2,
                            scale: 1.02,
                            ease: "power2.out"
                        });
                    }
                });

                card.addEventListener('mouseleave', () => {
                    if (!card.classList.contains('border-white')) {
                        gsap.to(card, {
                            duration: 0.2,
                            scale: 1,
                            ease: "power2.out"
                        });
                    }
                });
            });
        }

        // Initialize everything
        initializeAnimations();
        addHoverEffects();

        // Drag & Drop functionality
        const dropZone = document.getElementById('drop-zone');
        const dropZoneContent = document.getElementById('drop-zone-content');
        const previewContent = document.getElementById('preview-content');
        const previewImg = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');
        const previewSize = document.getElementById('preview-size');
        const removeFileBtn = document.getElementById('remove-file');
        const fileError = document.getElementById('file-error');

        // File validation function
        function validateFile(file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

            if (file.size > maxSize) {
                showError('File terlalu besar. Maksimal 5MB.');
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                showError('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                return false;
            }

            return true;
        }

        // Show error message
        function showError(message) {
            fileError.querySelector('p').textContent = message;
            fileError.classList.remove('hidden');
            setTimeout(() => {
                fileError.classList.add('hidden');
            }, 5000);
        }

        // Hide error message
        function hideError() {
            fileError.classList.add('hidden');
        }

        // Show preview with GSAP animation
        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewName.textContent = file.name;
                previewSize.textContent = formatFileSize(file.size);

                // Animate out drop zone content
                gsap.to(dropZoneContent, {
                    duration: 0.3,
                    opacity: 0,
                    scale: 0.9,
                    y: -10,
                    ease: "power2.in",
                    onComplete: () => {
                        dropZoneContent.classList.add('hidden');
                    }
                });

                // Animate in preview content
                previewContent.classList.remove('hidden');
                gsap.fromTo(previewContent,
                    { opacity: 0, scale: 0.9, y: 10 },
                    {
                        duration: 0.4,
                        opacity: 1,
                        scale: 1,
                        y: 0,
                        ease: "back.out(1.7)",
                        delay: 0.1
                    }
                );

                hideError();
            };
            reader.readAsDataURL(file);
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Reset file input with GSAP animation
        function resetFileInput() {
            fileInput.value = '';

            // Animate out preview content
            gsap.to(previewContent, {
                duration: 0.3,
                opacity: 0,
                scale: 0.9,
                y: 10,
                ease: "power2.in",
                onComplete: () => {
                    previewContent.classList.add('hidden');
                }
            });

            // Animate in drop zone content
            dropZoneContent.classList.remove('hidden');
            gsap.fromTo(dropZoneContent,
                { opacity: 0, scale: 0.9, y: -10 },
                {
                    duration: 0.4,
                    opacity: 1,
                    scale: 1,
                    y: 0,
                    ease: "back.out(1.7)",
                    delay: 0.1
                }
            );

            hideError();
        }

        // Handle file selection
        function handleFile(file) {
            if (validateFile(file)) {
                fileInput.files = new DataTransfer().files; // Clear existing files
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                showPreview(file);
            }
        }

        // Click to select file
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleFile(file);
            }
        });

        // Drag and drop events with GSAP animations
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('border-white/50', 'bg-white/10');

            // Animate drag over effect
            gsap.to(dropZone, {
                duration: 0.2,
                scale: 1.02,
                ease: "power2.out"
            });
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-white/50', 'bg-white/10');

            // Animate drag leave effect
            gsap.to(dropZone, {
                duration: 0.2,
                scale: 1,
                ease: "power2.out"
            });
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-white/50', 'bg-white/10');

            // Animate drop effect
            gsap.to(dropZone, {
                duration: 0.1,
                scale: 0.98,
                ease: "power2.out",
                onComplete: () => {
                    gsap.to(dropZone, {
                        duration: 0.2,
                        scale: 1,
                        ease: "back.out(1.7)"
                    });
                }
            });

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // Remove file
        removeFileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetFileInput();
        });

        // Initialize visual state
        updateVisualState('cash');
    });
</script>