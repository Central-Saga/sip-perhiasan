<?php
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use function Livewire\Volt\{ layout };
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
use Illuminate\Support\Arr;

layout('components.layouts.landing');

new class extends Component
{
    use WithFileUploads;

    public $metode_pembayaran = 'cash';
    public $tipe_pesanan = 'biasa';
    public $bukti_pembayaran = null;
    public $isProcessing = false;
    public $keranjangItems;
    public $total = 0;
    public $customRequest = null;
    public $user = null;
    public $pelanggan = null;

    public function mount()
    {
        // Redirect ke login jika belum login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->user = Auth::user();
        $this->pelanggan = Pelanggan::where('user_id', $this->user->id)->first();
        $this->loadCartData();

        // Set tipe pesanan otomatis berdasarkan custom request
        if ($this->customRequest) {
            $this->tipe_pesanan = 'custom';
        }
    }

    public function loadCartData()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $this->keranjangItems = collect();
                return;
            }

            $pelanggan = Pelanggan::where('user_id', $user->id)->first();

            if ($pelanggan) {
                $this->keranjangItems = Keranjang::with(['produk', 'customRequest'])
                    ->where('pelanggan_id', $pelanggan->id)
                    ->get();

                $this->total = $this->keranjangItems->sum(function ($item) {
                    return $item->subtotal ?? ($item->harga_satuan * $item->jumlah);
                });

                $this->customRequest = $this->keranjangItems->where('custom_request_id', '!=', null)->first()?->customRequest;
            } else {
                $this->keranjangItems = collect();
            }
        } catch (\Exception $e) {
            $this->keranjangItems = collect();
        }
    }

    public function processCheckout()
    {
        try {
            $this->isProcessing = true;

            // Check if user is authenticated
            if (!Auth::check()) {
                session()->flash('error', 'Anda harus login terlebih dahulu.');
                return;
            }

            // Check if keranjang is empty
            if (!$this->keranjangItems || $this->keranjangItems->isEmpty()) {
                session()->flash('error', 'Keranjang belanja kosong.');
                return;
            }

            // Validasi input
            $validationRules = [
                'metode_pembayaran' => 'required|in:cash,transfer',
                'tipe_pesanan' => 'required|in:biasa,custom',
            ];

            try {
                $this->validate($validationRules);
            } catch (\Illuminate\Validation\ValidationException $e) {
                session()->flash('error', 'Validasi gagal: ' . implode(', ', Arr::flatten($e->errors())));
                return;
            }

            // Validasi file manual untuk transfer
            if ($this->metode_pembayaran === 'transfer') {
                if (!$this->bukti_pembayaran) {
                    session()->flash('error', 'Bukti pembayaran harus diupload untuk metode transfer.');
                    return;
                }

                // Validasi tipe file
                if (is_object($this->bukti_pembayaran)) {
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!in_array($this->bukti_pembayaran->getMimeType(), $allowedTypes)) {
                        session()->flash('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                        return;
                    }

                    // Validasi ukuran file (5MB)
                    if ($this->bukti_pembayaran->getSize() > 5120 * 1024) {
                        session()->flash('error', 'File terlalu besar. Maksimal 5MB.');
                        return;
                    }
                }
            }

            // Handle file upload if exists
            $buktiTransferPath = null;
            if ($this->metode_pembayaran === 'transfer' && $this->bukti_pembayaran) {
                try {
                    $filename = 'bukti_transfer_' . time() . '_' . uniqid() . '.' . $this->bukti_pembayaran->getClientOriginalExtension();
                    $buktiTransferPath = $this->bukti_pembayaran->storeAs('bukti_pembayaran', $filename, 'public');
                } catch (\Exception $e) {
                    session()->flash('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
                    return;
                }
            }

            DB::beginTransaction();

            $user = Auth::user();
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();

            if (!$pelanggan) {
                session()->flash('error', 'Profil pelanggan tidak ditemukan.');
                return;
            } elseif ($this->keranjangItems->isEmpty()) {
                session()->flash('error', 'Keranjang belanja kosong.');
                return;
            }

            // Generate kode transaksi
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Buat transaksi
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'pelanggan_id' => $pelanggan->id,
                'kode_transaksi' => $kodeTransaksi,
                'total_harga' => $this->total,
                'status' => 'Pending',
                'tipe_pesanan' => $this->tipe_pesanan,
            ]);

            // Buat detail transaksi
            foreach ($this->keranjangItems as $item) {
                if ($item->produk) {
                    $detailTransaksi = DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item->produk_id,
                        'jumlah' => $item->jumlah,
                        'sub_total' => $item->subtotal ?? ($item->harga_satuan * $item->jumlah),
                    ]);

                    // Update stok produk
                    $produk = Produk::find($item->produk_id);
                    if ($produk) {
                        $produk->decrement('stok', $item->jumlah);
                    }
                } elseif ($item->customRequest) {
                    // Handle custom request
                    $detailTransaksi = DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => null, // Custom request tidak punya produk_id
                        'custom_request_id' => $item->custom_request_id, // Simpan custom_request_id
                        'jumlah' => $item->jumlah,
                        'sub_total' => $item->subtotal ?? ($item->harga_satuan * $item->jumlah),
                    ]);
                }
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
                $transaksi->update(['status' => 'Diproses']);
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

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }
}
?>

<div x-data="checkoutApp()" x-init="init()">
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

                    @if($this->keranjangItems && $this->keranjangItems->count() > 0)
                    <div class="space-y-4">
                        @foreach($this->keranjangItems as $item)
                        <div class="flex gap-6 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                            <div class="w-20 h-20 flex-shrink-0">
                                @if($item->produk && $item->produk->foto)
                                <img src="{{ Storage::url($item->produk->foto) }}"
                                    alt="{{ $item->produk->nama_produk }}"
                                    class="w-full h-full object-cover rounded-xl border border-slate-200 dark:border-slate-600" />
                                @elseif($item->customRequest)
                                @if($item->customRequest->gambar_referensi)
                                <img src="{{ Storage::url($item->customRequest->gambar_referensi) }}"
                                    alt="Custom Request"
                                    class="w-full h-full object-cover rounded-xl border border-slate-200 dark:border-slate-600" />
                                @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl border-2 border-dashed border-purple-200 dark:border-purple-700">
                                    <i
                                        class="fa-solid fa-wand-magic-sparkles text-2xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                @endif
                                @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl border-2 border-dashed border-purple-200 dark:border-purple-700">
                                    <i class="fa-solid fa-gem text-2xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                @if($item->produk)
                                <h4 class="font-bold text-slate-800 dark:text-slate-100 text-lg">{{
                                    $item->produk->nama_produk }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Kategori: {{
                                        $item->produk->kategori ?? '-' }}</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">•</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Qty: {{ $item->jumlah
                                        }}</span>
                                </div>
                                @elseif($item->customRequest)
                                <h4 class="font-bold text-slate-800 dark:text-slate-100 text-lg">Pesanan Custom</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Deskripsi: {{
                                        Str::limit($item->customRequest->deskripsi, 50) ?? '-' }}</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">•</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Qty: {{ $item->jumlah
                                        }}</span>
                                </div>
                                @endif
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

                @if($this->keranjangItems && $this->keranjangItems->count() > 0)

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
                                <label class="group relative cursor-pointer" @click="selectPaymentMethod('cash')">
                                    <input type="radio" wire:model="metode_pembayaran" name="metode_pembayaran"
                                        value="cash" class="sr-only">
                                    <div id="cash-card"
                                        class="p-6 border-2 border-white bg-white/20 backdrop-blur-sm rounded-2xl transition-all duration-300 group-hover:bg-white/30 group-hover:border-white"
                                        :class="selectedMethod === 'cash' ? 'border-white bg-white/20' : 'border-white/30 bg-white/10'">
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
                                                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center"
                                                    :class="selectedMethod === 'cash' ? 'border-white bg-white' : 'border-white/50'">
                                                    <i class="fa-solid fa-check text-emerald-600 text-xs"
                                                        x-show="selectedMethod === 'cash'"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="group relative cursor-pointer" @click="selectPaymentMethod('transfer')">
                                    <input type="radio" wire:model="metode_pembayaran" name="metode_pembayaran"
                                        value="transfer" class="sr-only">
                                    <div id="transfer-card"
                                        class="p-6 border-2 backdrop-blur-sm rounded-2xl transition-all duration-300 group-hover:bg-white/20 group-hover:border-white/50"
                                        :class="selectedMethod === 'transfer' ? 'border-white bg-white/20' : 'border-white/30 bg-white/10'">
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
                                                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center"
                                                    :class="selectedMethod === 'transfer' ? 'border-white bg-white' : 'border-white/50'">
                                                    <i class="fa-solid fa-check text-emerald-600 text-xs"
                                                        x-show="selectedMethod === 'transfer'"></i>
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
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl overflow-hidden"
                        x-show="$wire.metode_pembayaran === 'transfer'"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4">
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
                                    class="relative border-2 border-dashed rounded-xl p-8 text-center transition-all duration-300"
                                    :class="isDragOver ? 'border-white/50 bg-white/10' : 'border-white/30 hover:border-white/50 hover:bg-white/5'"
                                    @dragover="handleDragOver($event)" @dragleave="handleDragLeave($event)"
                                    @drop="handleDrop($event)" @click="$refs.fileInput.click()">
                                    <input type="file" wire:model="bukti_pembayaran" x-ref="fileInput"
                                        id="bukti_pembayaran" accept="image/jpeg,image/jpg,image/png,application/pdf"
                                        class="hidden" @change="handleFileChange($event)">

                                    <!-- Default State -->
                                    <div id="drop-zone-content" class="space-y-4" x-show="!filePreview">
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
                                    <div id="preview-content" x-show="filePreview" x-transition>
                                        <div class="flex items-center justify-center space-x-4">
                                            <div id="preview-image"
                                                class="w-20 h-20 rounded-lg overflow-hidden border-2 border-white/30">
                                                <img x-show="filePreview?.src" :src="filePreview?.src" alt="Preview"
                                                    class="w-full h-full object-cover">
                                                <div x-show="!filePreview?.src"
                                                    class="w-full h-full flex items-center justify-center bg-green-500/20 border-2 border-green-400/30">
                                                    <i class="fa-solid fa-check text-green-400 text-2xl"></i>
                                                </div>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-white font-medium" x-text="filePreview?.name"></p>
                                                <p class="text-white/70 text-sm" x-text="filePreview?.size"></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <button type="button" @click="removeFile()"
                                                        class="text-red-300 hover:text-red-200 text-sm">
                                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                                    </button>
                                                    <span x-show="!filePreview?.src" class="text-green-300 text-xs">
                                                        <i class="fa-solid fa-check mr-1"></i>File siap diupload
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div x-show="fileError" x-transition
                                    class="mt-2 p-2 bg-red-500/20 border border-red-500/30 rounded-lg">
                                    <p class="text-red-200 text-sm" x-text="fileError"></p>
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
                                @if($this->customRequest)
                                <!-- Custom Request - Auto Selected & Disabled -->
                                <div
                                    class="p-6 border-2 border-purple-400 bg-purple-500/20 backdrop-blur-sm rounded-2xl">
                                    <div class="flex items-center gap-4">
                                        <i class="fa-solid fa-wand-magic-sparkles text-white text-2xl"></i>
                                        <div class="flex-grow">
                                            <div class="font-bold text-white text-lg">Pesanan Custom</div>
                                            <div class="text-white/80">Produk sesuai permintaan khusus</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-lock text-white/70"></i>
                                            <span class="text-white/70 text-sm">Otomatis</span>
                                        </div>
                                    </div>
                                    <input type="hidden" wire:model="tipe_pesanan" value="custom">
                                </div>

                                <!-- Info bahwa pesanan biasa tidak tersedia -->
                                <div class="p-4 bg-slate-500/20 border border-slate-400/30 rounded-xl">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-info-circle text-white/70"></i>
                                        <div class="text-sm text-white/80">
                                            <p class="font-medium">Pesanan Custom Terdeteksi</p>
                                            <p>Tipe pesanan otomatis diset ke "Custom" karena ada permintaan khusus
                                                dalam keranjang.</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <!-- Normal Order Selection -->
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
</script>

<script>
    function checkoutApp() {
        return {
            // Payment method state
            selectedMethod: 'cash',
            showUploadSection: false,

            // File upload state
            uploadedFile: null,
            filePreview: null,
            fileError: null,
            isDragOver: false,

            // GSAP timeline
            timeline: null,

            init() {
                // Initialize GSAP
                this.timeline = gsap.timeline();

                // Set initial state based on Livewire value
                this.selectedMethod = this.$wire.metode_pembayaran || 'cash';
                this.updateVisualState(this.selectedMethod);

                // Listen for Livewire updates
                this.$watch('$wire.metode_pembayaran', (value) => {
                    this.selectedMethod = value;
                    this.updateVisualState(value);
                });

                // Listen for file uploads
                this.$watch('$wire.bukti_pembayaran', (file) => {
                    if (file) {
                        this.handleFileUpload(file);
                    }
                });

            },

            // Payment method selection
            selectPaymentMethod(method) {
                this.selectedMethod = method;
                this.showUploadSection = method === 'transfer';

                // Update Livewire state first
                this.$wire.set('metode_pembayaran', method);

                // Then update visual state
                this.updateVisualState(method);
            },

            // Update visual state with GSAP animations
            updateVisualState(method) {
                const cashCard = document.getElementById('cash-card');
                const transferCard = document.getElementById('transfer-card');
                const cashCheck = document.getElementById('cash-check');
                const transferCheck = document.getElementById('transfer-check');

                if (method === 'cash') {
                    // Cash selected
                    this.animateCardSelection(cashCard, cashCheck, true);
                    this.animateCardSelection(transferCard, transferCheck, false);
                } else if (method === 'transfer') {
                    // Transfer selected
                    this.animateCardSelection(transferCard, transferCheck, true);
                    this.animateCardSelection(cashCard, cashCheck, false);
                }
            },

            // Animate card selection
            animateCardSelection(card, check, isSelected) {
                if (isSelected) {
                    // Selected state
                    gsap.to(card, {
                        duration: 0.3,
                        scale: 1.02,
                        ease: "power2.out",
                        onComplete: () => {
                            gsap.to(card, { duration: 0.2, scale: 1, ease: "power2.out" });
                        }
                    });

                    gsap.fromTo(check,
                        { scale: 0, opacity: 0 },
                        {
                            duration: 0.4,
                            scale: 1,
                            opacity: 1,
                            ease: "back.out(1.7)",
                            onStart: () => {
                                card.classList.remove('border-white/30', 'bg-white/10');
                                card.classList.add('border-white', 'bg-white/20');
                                check.classList.remove('border-white/50');
                                check.classList.add('border-white', 'bg-white');
                                check.innerHTML = '<i class="fa-solid fa-check text-emerald-600 text-xs"></i>';
                            }
                        }
                    );
                } else {
                    // Deselected state
                    gsap.to(card, {
                        duration: 0.3,
                        scale: 0.98,
                        ease: "power2.out",
                        onComplete: () => {
                            gsap.to(card, { duration: 0.2, scale: 1, ease: "power2.out" });
                        }
                    });

                    gsap.to(check, {
                        duration: 0.2,
                        scale: 0,
                        opacity: 0,
                        ease: "power2.in",
                        onComplete: () => {
                            card.classList.remove('border-white', 'bg-white/20');
                            card.classList.add('border-white/30', 'bg-white/10');
                            check.classList.remove('border-white', 'bg-white');
                            check.classList.add('border-white/50');
                            check.innerHTML = '';
                        }
                    });
                }
            },


            // Handle file input change
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.handleFileUpload(file);
                }
            },

            // File upload handling
            handleFileUpload(file) {
                if (!file) {
                    return;
                }

                this.uploadedFile = file;
                this.fileError = null;

                // Check if this is a Livewire file object
                if (typeof file === 'string' || (file && file.constructor && file.constructor.name === 'Proxy')) {
                    // Livewire file object - show basic info
                    this.filePreview = {
                        src: null,
                        name: 'File uploaded successfully',
                        size: 'Ready for upload'
                    };
                    return;
                }

                // Regular file object
                // Validate file
                if (!this.validateFile(file)) {
                    return;
                }

                // Show preview
                this.showFilePreview(file);
            },

            // File validation
            validateFile(file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/pjpeg', // Alternative JPEG MIME type
                    'application/pdf'
                ];

                // Handle Livewire file objects or files with undefined properties
                if (!file || (file.name === undefined && file.type === undefined && file.size === undefined)) {
                    return true; // Let Livewire handle the validation
                }

                // Check if file has valid properties
                if (file.name === undefined || file.type === undefined || file.size === undefined) {
                    return true; // Let Livewire handle the validation
                }

                if (file.size > maxSize) {
                    this.fileError = 'File terlalu besar. Maksimal 5MB.';
                    return false;
                }

                // Check file extension as fallback
                const fileName = file.name ? file.name.toLowerCase() : '';
                const allowedExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];
                const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (!allowedTypes.includes(file.type) && !hasValidExtension) {
                    this.fileError = 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.';
                    return false;
                }

                return true;
            },

            // Show file preview
            showFilePreview(file) {
                // Handle Livewire file objects
                if (!file || (file.name === undefined && file.type === undefined && file.size === undefined)) {
                    this.filePreview = {
                        src: null,
                        name: 'File uploaded successfully',
                        size: 'Ready for upload'
                    };
                    return;
                }

                // Handle files with undefined properties
                if (file.name === undefined || file.type === undefined || file.size === undefined) {
                    this.filePreview = {
                        src: null,
                        name: 'File uploaded successfully',
                        size: 'Ready for upload'
                    };
                    return;
                }

                // Regular file object - use FileReader
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.filePreview = {
                        src: e.target.result,
                        name: file.name,
                        size: this.formatFileSize(file.size)
                    };
                };
                reader.readAsDataURL(file);
            },

            // Format file size
            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            // Remove file
            removeFile() {
                this.uploadedFile = null;
                this.filePreview = null;
                this.fileError = null;

                // Clear file input
                const fileInput = document.getElementById('bukti_pembayaran');
                if (fileInput) {
                    fileInput.value = '';
                }

                // Update Livewire state
                this.$wire.set('bukti_pembayaran', null);
            },

            // Drag and drop handlers
            handleDragOver(e) {
                e.preventDefault();
                this.isDragOver = true;
            },

            handleDragLeave(e) {
                e.preventDefault();
                this.isDragOver = false;
            },

            handleDrop(e) {
                e.preventDefault();
                this.isDragOver = false;

                const files = e.dataTransfer.files;

                if (files.length > 0) {
                    this.handleFileUpload(files[0]);
                }
            }
        }
    }
</script>
