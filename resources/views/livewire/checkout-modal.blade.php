<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\CustomRequest;
use App\Models\Pengiriman;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

layout('components.layouts.landing');

new class extends Component {
    public $showModal = false;
    public $keranjangItems = [];
    public $customRequest = null;
    public $total = 0;
    public $nama_penerima = '';
    public $alamat_pengiriman = '';
    public $no_telepon = '';
    public $metode_pembayaran = 'transfer';
    public $catatan = '';

    public function mount() {
        $this->loadCartData();
    }

    public function loadCartData() {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return;
        }

        $this->keranjangItems = Keranjang::with(['produk', 'customRequest'])
            ->where('pelanggan_id', $pelanggan->id)
            ->get();

        // Hitung total
        $this->total = 0;
        foreach ($this->keranjangItems as $item) {
            if ($item->produk_id) {
                $this->total += $item->subtotal ?? ($item->harga_satuan * $item->jumlah);
            }
        }

        // Ambil custom request jika ada
        $this->customRequest = $this->keranjangItems->where('custom_request_id', '!=', null)->first()?->customRequest;

        // Set default data pelanggan
        $this->nama_penerima = $user->name;
        $this->alamat_pengiriman = $pelanggan->alamat ?? '';
        $this->no_telepon = $pelanggan->no_telepon ?? '';
    }

    public function openModal() {
        $this->loadCartData();
        if ($this->keranjangItems->count() == 0) {
            session()->flash('error', 'Keranjang kosong');
            return;
        }
        $this->showModal = true;
    }

    public function closeModal() {
        $this->showModal = false;
        $this->reset(['nama_penerima', 'alamat_pengiriman', 'no_telepon', 'metode_pembayaran', 'catatan']);
    }

    public function checkout() {
        $this->validate([
            'nama_penerima' => 'required|string|max:255',
            'alamat_pengiriman' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'metode_pembayaran' => 'required|in:transfer,cash',
        ]);

        if ($this->keranjangItems->count() == 0) {
            session()->flash('error', 'Keranjang kosong');
            return;
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();

            // Buat transaksi
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'pelanggan_id' => $pelanggan->id,
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . Str::random(6),
                'total_harga' => $this->total,
                'status' => 'pending',
                'tipe_pesanan' => 'ready',
            ]);

            // Buat detail transaksi untuk setiap item di keranjang
            foreach ($this->keranjangItems as $item) {
                if ($item->produk_id) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item->produk_id,
                        'jumlah' => $item->jumlah,
                        'harga_satuan' => $item->harga_satuan,
                        'subtotal' => $item->subtotal ?? ($item->harga_satuan * $item->jumlah),
                    ]);
                }
            }

            // Buat pengiriman
            Pengiriman::create([
                'transaksi_id' => $transaksi->id,
                'status' => 'pending',
                'deskripsi' => "Penerima: {$this->nama_penerima}, Alamat: {$this->alamat_pengiriman}, Telepon: {$this->no_telepon}" . ($this->catatan ? ", Catatan: {$this->catatan}" : ''),
                'tanggal_pengiriman' => null,
            ]);

            // Buat pembayaran
            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode' => $this->metode_pembayaran,
                'status' => 'PENDING',
                'tanggal_bayar' => null,
            ]);

            // Hapus semua item dari keranjang
            Keranjang::where('pelanggan_id', $pelanggan->id)->delete();

            DB::commit();

            $this->closeModal();
            session()->flash('success', 'Transaksi berhasil dibuat!');
            return redirect()->route('transaksi.detail', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }
};

?>

<div>
    <!-- Modal Trigger Button -->
    <button wire:click="openModal"
        class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-credit-card"></i> Checkout
    </button>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }" x-show="show"
        x-transition>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-on:click="show = false"></div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-slate-800 px-6 py-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-indigo-500"></i> Checkout
                        </h3>
                        <button wire:click="closeModal"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    @if (session()->has('error'))
                    <div
                        class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
                            <i class="fa-solid fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Cart Items Summary -->
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 mb-6">
                        <h4
                            class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-cart-shopping text-indigo-400"></i> Produk di Keranjang
                        </h4>

                        <div class="space-y-3 max-h-48 overflow-y-auto">
                            @foreach($keranjangItems as $item)
                            @if($item->produk)
                            <div
                                class="flex gap-3 p-2 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-600">
                                <div class="w-12 h-12 flex-shrink-0">
                                    @if($item->produk->foto)
                                    <img src="{{ Storage::url($item->produk->foto) }}"
                                        alt="{{ $item->produk->nama_produk }}"
                                        class="w-full h-full object-cover rounded border border-slate-200 dark:border-slate-600">
                                    @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-slate-200 dark:bg-slate-600 rounded border border-slate-300 dark:border-slate-500">
                                        <i class="fa-solid fa-gem text-slate-400"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <div class="font-medium text-slate-800 dark:text-slate-100 text-sm">
                                        {{ $item->produk->nama_produk }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $item->jumlah }} x {{ formatRupiah($item->harga_satuan) }}
                                    </div>
                                </div>
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ formatRupiah($item->subtotal ?? ($item->harga_satuan * $item->jumlah)) }}
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <div
                            class="flex justify-between items-center border-t border-slate-200 dark:border-slate-600 pt-3 mt-3">
                            <span class="font-bold text-slate-800 dark:text-slate-100">Total:</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-300 text-lg">{{ formatRupiah($total)
                                }}</span>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <form wire:submit="checkout" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_penerima"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Nama Penerima
                                </label>
                                <input type="text" id="nama_penerima" wire:model="nama_penerima"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100 text-sm"
                                    required>
                                @error('nama_penerima') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="no_telepon"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    No. Telepon
                                </label>
                                <input type="text" id="no_telepon" wire:model="no_telepon"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100 text-sm"
                                    required>
                                @error('no_telepon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alamat_pengiriman"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Alamat Pengiriman
                            </label>
                            <textarea id="alamat_pengiriman" wire:model="alamat_pengiriman" rows="2"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100 text-sm"
                                required></textarea>
                            @error('alamat_pengiriman') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="metode_pembayaran"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Metode Pembayaran
                            </label>
                            <select id="metode_pembayaran" wire:model="metode_pembayaran"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100 text-sm">
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Cash on Delivery</option>
                            </select>
                            @error('metode_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Catatan (Opsional)
                            </label>
                            <textarea id="catatan" wire:model="catatan" rows="2"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100 text-sm"
                                placeholder="Catatan khusus untuk pesanan..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="button" wire:click="closeModal"
                                class="flex-1 px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition text-sm">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
                                <i class="fa-solid fa-credit-card"></i> Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>