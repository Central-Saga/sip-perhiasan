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
    public $keranjangItems = [];
    public $customRequest = null;
    public $total = 0;
    public $nama_penerima = '';
    public $alamat_pengiriman = '';
    public $no_telepon = '';
    public $metode_pembayaran = 'transfer';
    public $catatan = '';

    public function mount() {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return redirect()->route('home');
        }

        $this->keranjangItems = Keranjang::with(['produk', 'customRequest'])
            ->where('pelanggan_id', $pelanggan->id)
            ->get();

        // Hitung total
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

    public function checkout() {
        $this->validate([
            'nama_penerima' => 'required|string|max:255',
            'alamat_pengiriman' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'metode_pembayaran' => 'required|in:transfer,cash,credit_card',
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
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . Str::random(6),
                'pelanggan_id' => $pelanggan->id,
                'tanggal_transaksi' => now(),
                'total_harga' => $this->total,
                'status' => 'pending',
                'metode_pembayaran' => $this->metode_pembayaran,
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
                'nama_penerima' => $this->nama_penerima,
                'alamat_pengiriman' => $this->alamat_pengiriman,
                'no_telepon' => $this->no_telepon,
                'status' => 'pending',
                'catatan' => $this->catatan,
            ]);

            // Buat pembayaran
            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode_pembayaran' => $this->metode_pembayaran,
                'status' => 'pending',
                'total_pembayaran' => $this->total,
            ]);

            // Hapus semua item dari keranjang
            Keranjang::where('pelanggan_id', $pelanggan->id)->delete();

            DB::commit();

            session()->flash('success', 'Transaksi berhasil dibuat!');
            return redirect()->route('transaksi.detail', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }
};

?>

<div class="max-w-2xl mx-auto px-4 py-12">
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

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-bold mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-indigo-400"></i> Produk di Keranjang
        </h2>

        @if($keranjangItems->count() > 0)
        <div class="mb-6 divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($keranjangItems as $item)
            @if($item->produk)
            <div class="py-4">
                <div class="flex gap-4">
                    <div class="w-20 h-20 flex-shrink-0">
                        @if($item->produk->foto)
                        <img src="{{ Storage::url($item->produk->foto) }}" alt="{{ $item->produk->nama_produk }}"
                            class="w-full h-full object-cover rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700" />
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-gem text-xl text-slate-400 dark:text-slate-500"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-semibold text-slate-800 dark:text-slate-100">{{ $item->produk->nama_produk }}
                        </h4>
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $item->jumlah }} x {{ formatRupiah($item->harga_satuan) }}
                        </div>
                        <div class="text-indigo-600 dark:text-indigo-300 font-semibold mt-1">
                            {{ formatRupiah($item->subtotal ?? ($item->harga_satuan * $item->jumlah)) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-4 mb-6">
            <span class="font-bold text-lg text-slate-800 dark:text-slate-100">Total:</span>
            <span class="font-bold text-indigo-600 dark:text-indigo-300 text-lg">{{ formatRupiah($total) }}</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-bold mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2">
            <i class="fa-solid fa-shipping-fast text-indigo-400"></i> Informasi Pengiriman
        </h2>

        <form wire:submit="checkout" class="space-y-6">
            <div>
                <label for="nama_penerima" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Nama Penerima
                </label>
                <input type="text" id="nama_penerima" wire:model="nama_penerima"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100"
                    required>
                @error('nama_penerima') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="alamat_pengiriman"
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Alamat Pengiriman
                </label>
                <textarea id="alamat_pengiriman" wire:model="alamat_pengiriman" rows="3"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100"
                    required></textarea>
                @error('alamat_pengiriman') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="no_telepon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    No. Telepon
                </label>
                <input type="text" id="no_telepon" wire:model="no_telepon"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100"
                    required>
                @error('no_telepon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="metode_pembayaran"
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Metode Pembayaran
                </label>
                <select id="metode_pembayaran" wire:model="metode_pembayaran"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100">
                    <option value="transfer">Transfer Bank</option>
                    <option value="cash">Cash on Delivery</option>
                    <option value="credit_card">Credit Card</option>
                </select>
                @error('metode_pembayaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea id="catatan" wire:model="catatan" rows="2"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-100"
                    placeholder="Catatan khusus untuk pesanan..."></textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('cart') }}"
                    class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
                </a>
                <button type="submit"
                    class="flex-1 sm:flex-none sm:px-5 sm:py-3 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i> Proses Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>