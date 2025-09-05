<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

layout('components.layouts.landing');

new class extends Component {
    public $transaksi;

    public function mount($id) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return redirect()->route('home');
        }

        $this->transaksi = Transaksi::with(['detailTransaksi.produk', 'pengiriman', 'pembayaran', 'pelanggan.user'])
            ->where('id', $id)
            ->where('pelanggan_id', $pelanggan->id)
            ->first();

        if (!$this->transaksi) {
            abort(404, 'Transaksi tidak ditemukan');
        }
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
    <div class="mb-6">
        <a href="{{ route('transaksi') }}"
            class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-100 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Transaksi
        </a>
    </div>

    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-file-invoice-dollar text-indigo-500"></i> Detail Transaksi
    </h1>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                    {{ $transaksi->kode_transaksi }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400">
                    {{ $transaksi->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="flex flex-col md:items-end gap-2">
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ getStatusColor($transaksi->status) }}">
                    <i class="fa-solid {{ getStatusIcon($transaksi->status) }} mr-2"></i>
                    {{ ucfirst($transaksi->status) }}
                </span>
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">
                    {{ formatRupiah($transaksi->total_harga) }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user text-indigo-500"></i> Informasi Pelanggan
                </h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Nama:</strong> {{ $transaksi->pelanggan->user->name }}</div>
                    <div><strong>Email:</strong> {{ $transaksi->pelanggan->user->email }}</div>
                    @if($transaksi->pelanggan->no_telepon)
                    <div><strong>Telepon:</strong> {{ $transaksi->pelanggan->no_telepon }}</div>
                    @endif
                </div>
            </div>

            @if($transaksi->pembayaran)
            <div>
                <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-indigo-500"></i> Informasi Pembayaran
                </h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Metode:</strong> {{ ucfirst($transaksi->pembayaran->metode) }}</div>
                    <div><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-xs {{ getStatusColor($transaksi->pembayaran->status) }}">
                            {{ ucfirst($transaksi->pembayaran->status) }}
                        </span>
                    </div>
                    <div><strong>Total:</strong> {{ formatRupiah($transaksi->total_harga) }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shopping-bag text-indigo-500"></i> Produk yang Dibeli
        </h3>

        <div class="space-y-4">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="flex gap-4 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <div class="w-20 h-20 flex-shrink-0">
                    @if($detail->produk->foto)
                    <img src="{{ Storage::url($detail->produk->foto) }}" alt="{{ $detail->produk->nama_produk }}"
                        class="w-full h-full object-cover rounded border border-slate-200 dark:border-slate-600">
                    @else
                    <div
                        class="w-full h-full flex items-center justify-center bg-slate-200 dark:bg-slate-600 rounded border border-slate-300 dark:border-slate-500">
                        <i class="fa-solid fa-gem text-slate-400 text-xl"></i>
                    </div>
                    @endif
                </div>
                <div class="flex-grow">
                    <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">
                        {{ $detail->produk->nama_produk }}
                    </h4>
                    <div class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                        Kategori: {{ $detail->produk->kategori ?? '-' }}
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $detail->jumlah }} x {{ formatRupiah($detail->harga_satuan) }}
                        </div>
                        <div class="font-semibold text-slate-800 dark:text-slate-100">
                            {{ formatRupiah($detail->subtotal) }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
            <div class="flex justify-between items-center text-lg font-bold">
                <span class="text-slate-800 dark:text-slate-100">Total:</span>
                <span class="text-indigo-600 dark:text-indigo-300">{{ formatRupiah($transaksi->total_harga) }}</span>
            </div>
        </div>
    </div>

    @if($transaksi->pengiriman)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shipping-fast text-indigo-500"></i> Informasi Pengiriman
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat Pengiriman</h4>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    <div><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</div>
                    <div><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_pengiriman }}</div>
                    <div><strong>Telepon:</strong> {{ $transaksi->pengiriman->no_telepon }}</div>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-slate-700 dark:text-slate-300 mb-2">Status Pengiriman</h4>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    <div><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-xs {{ getStatusColor($transaksi->pengiriman->status) }}">
                            {{ ucfirst($transaksi->pengiriman->status) }}
                        </span>
                    </div>
                    @if($transaksi->pengiriman->catatan)
                    <div class="mt-2"><strong>Catatan:</strong> {{ $transaksi->pengiriman->catatan }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>