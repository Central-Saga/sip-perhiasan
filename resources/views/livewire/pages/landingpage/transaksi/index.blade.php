<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

layout('components.layouts.landing');

new class extends Component {
    public $transaksis = [];

    public function mount() {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return redirect()->route('home');
        }

        $this->transaksis = Transaksi::with(['detailTransaksi.produk', 'pengiriman', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
};


function getStatusColor($status) {
    return match($status) {
        'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'Diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'Dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300'
    };
}

function getStatusIcon($status) {
    return match($status) {
        'Pending' => 'fa-clock',
        'Diproses' => 'fa-cog',
        'Selesai' => 'fa-check-circle',
        'Dibatalkan' => 'fa-times-circle',
        default => 'fa-question-circle'
    };
}
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-file-invoice-dollar text-indigo-500"></i> Riwayat Transaksi
    </h1>

    @if($transaksis->count() > 0)
    <div class="space-y-6">
        @foreach($transaksis as $transaksi)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                        {{ $transaksi->kode_transaksi }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $transaksi->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="flex flex-col md:items-end gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ getStatusColor($transaksi->status) }}">
                        <i class="fa-solid {{ getStatusIcon($transaksi->status) }} mr-1"></i>
                        {{ ucfirst($transaksi->status) }}
                    </span>
                    <div class="text-lg font-bold text-indigo-600 dark:text-indigo-300">
                        {{ formatRupiah($transaksi->total_harga) }}
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Produk:</h4>
                <div class="space-y-2">
                    @foreach($transaksi->detailTransaksi as $detail)
                    <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        @if($detail->produk->foto)
                        <img src="{{ Storage::url($detail->produk->foto) }}" alt="{{ $detail->produk->nama_produk }}"
                            class="w-12 h-12 object-cover rounded border border-slate-200 dark:border-slate-600">
                        @else
                        <div
                            class="w-12 h-12 flex items-center justify-center bg-slate-200 dark:bg-slate-600 rounded border border-slate-300 dark:border-slate-500">
                            <i class="fa-solid fa-gem text-slate-400"></i>
                        </div>
                        @endif
                        <div class="flex-grow">
                            <div class="font-medium text-slate-800 dark:text-slate-100">
                                {{ $detail->produk->nama_produk }}
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $detail->jumlah }} x {{ formatRupiah($detail->harga_satuan) }}
                            </div>
                        </div>
                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                            {{ formatRupiah($detail->subtotal) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($transaksi->pengiriman)
            <div class="mb-4 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-shipping-fast text-indigo-500"></i> Informasi Pengiriman
                </h4>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    <div><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</div>
                    <div><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_pengiriman }}</div>
                    <div><strong>Telepon:</strong> {{ $transaksi->pengiriman->no_telepon }}</div>
                    @if($transaksi->pengiriman->catatan)
                    <div><strong>Catatan:</strong> {{ $transaksi->pengiriman->catatan }}</div>
                    @endif
                </div>
            </div>
            @endif

            @if($transaksi->pembayaran)
            <div class="mb-4 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-indigo-500"></i> Informasi Pembayaran
                </h4>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    <div><strong>Metode:</strong> {{ ucfirst($transaksi->pembayaran->metode) }}</div>
                    <div><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-xs {{ getStatusColor($transaksi->pembayaran->status) }}">
                            {{ ucfirst($transaksi->pembayaran->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('transaksi.detail', $transaksi->id) }}"
                    class="flex-1 sm:flex-none px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-eye"></i> Lihat Detail
                </a>
                @if($transaksi->status === 'Pending')
                <button
                    class="flex-1 sm:flex-none px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-times"></i> Batalkan
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <i class="fa-solid fa-file-invoice text-slate-300 dark:text-slate-600 text-6xl mb-4"></i>
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Belum Ada Transaksi</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-6">Anda belum melakukan transaksi apapun.</p>
        <a href="{{ route('produk') }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-semibold transition">
            <i class="fa-solid fa-shopping-bag"></i> Mulai Belanja
        </a>
    </div>
    @endif
</div>