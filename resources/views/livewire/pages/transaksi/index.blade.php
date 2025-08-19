
<?php
use Livewire\Volt\Component;
use App\Models\Transaksi;

new class extends Component {
    public $search = '';
    public $sortField = 'tanggal_transaksi';
    public $sortDirection = 'desc';
    public $page = 1;
    public $openDetail = null;

    public function with() {
        return [
            'transaksis' => Transaksi::query()
                ->with(['pelanggan.user', 'detailTransaksi.produk'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->whereHas('pelanggan.user', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('produk', function ($p) {
                            $p->where('nama_produk', 'like', '%' . $this->search . '%');
                        });
                    });
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10)
        ];
    }

    public function delete($id) {
        Transaksi::find($id)->delete();
    }
};
?>

    <div class="max-w-full">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm">
                        <flux:icon name="currency-dollar" class="h-8 w-8 text-indigo-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Daftar Transaksi</h2>
                        <p class="mt-1 text-sm text-gray-600 flex items-center">
                            <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                            Kelola semua transaksi penjualan perhiasan
                        </p>
                    </div>
                </div>
                <a href="{{ route('transaksi.create') }}" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                    <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                    Tambah Transaksi
                </a>
            </div>
        </div>

        <!-- Search and Stats Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <!-- Search Bar -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                    </div>
                    <input wire:model.live="search" 
                        type="search" 
                        class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all duration-200"
                        placeholder="Cari berdasarkan pelanggan atau produk..." />
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-semibold text-indigo-600">{{ $transaksis->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <flux:icon name="shopping-cart" class="h-6 w-6 text-indigo-600" />
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Halaman</p>
                        <p class="text-2xl font-semibold text-purple-600">{{ $transaksis->currentPage() }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <flux:icon name="view-columns" class="h-6 w-6 text-purple-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="user" class="h-4 w-4" />
                                    <span>Pelanggan</span>
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="banknotes" class="h-4 w-4" />
                                    <span>Total Harga</span>
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="check-circle" class="h-4 w-4" />
                                    <span>Status</span>
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="calendar" class="h-4 w-4" />
                                    <span>Tanggal</span>
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-1">
                                    <flux:icon name="cog" class="h-4 w-4" />
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transaksis as $transaksi)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                            <flux:icon name="user" class="h-4 w-4 text-indigo-600" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $transaksi->pelanggan->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $transaksi->pelanggan_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                        <flux:icon name="banknotes" class="h-3 w-3" />
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                        :class="[
                                            $transaksi->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 ring-emerald-700/10' : '',
                                            $transaksi->status === 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-700/10' : '',
                                            $transaksi->status === 'diproses' ? 'bg-blue-50 text-blue-700 ring-blue-700/10' : '',
                                            $transaksi->status === 'dibatalkan' ? 'bg-red-50 text-red-700 ring-red-700/10' : ''
                                        ]">
                                        <flux:icon name="{{
                                            $transaksi->status === 'selesai' ? 'check-circle' :
                                            ($transaksi->status === 'pending' ? 'clock' :
                                            ($transaksi->status === 'diproses' ? 'arrow-path' :
                                            ($transaksi->status === 'dibatalkan' ? 'x-circle' : 'question-mark-circle')))
                                        }}" class="h-3 w-3" />
                                        {{ ucfirst($transaksi->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <flux:icon name="calendar" class="h-4 w-4 text-gray-400 mr-1" />
                                        <span class="font-medium text-gray-900">{{ $transaksi->tanggal_transaksi->format('d M Y') }}</span>
                                        <span class="ml-2 text-xs text-gray-500">{{ $transaksi->tanggal_transaksi->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('transaksi.show', $transaksi) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                            wire:navigate>
                                            <flux:icon name="eye" class="h-4 w-4 mr-1" />
                                            Detail
                                        </a>
                                        <a href="{{ route('transaksi.edit', $transaksi) }}" 
                                            class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150"
                                            wire:navigate>
                                            <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                            Edit
                                        </a>
                                        <button wire:click="delete({{ $transaksi->id }})" 
                                            class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                            wire:confirm="Apakah anda yakin ingin menghapus data ini?">
                                            <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-indigo-50/50 transition-all duration-300">
                                <td colspan="5" class="px-8 py-4">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-indigo-700 mb-2 flex items-center gap-2">
                                            <flux:icon name="view-columns" class="h-4 w-4 text-indigo-500" />
                                            Detail Transaksi Produk
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                            @foreach($transaksi->detailTransaksi as $detail)
                                                <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm">
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                                        <flux:icon name="sparkles" class="h-3 w-3" />
                                                        {{ $detail->produk->nama_produk ?? '-' }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                                        <flux:icon name="cube" class="h-3 w-3" />
                                                        {{ $detail->jumlah }} unit
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                                        <flux:icon name="banknotes" class="h-3 w-3" />
                                                        Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="relative">
                                            <div class="h-24 w-24 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse">
                                                <flux:icon name="shopping-cart" class="h-12 w-12 text-gray-400" />
                                            </div>
                                            <div class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                                <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                            </div>
                                        </div>
                                        <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        <div class="mt-6">
            <div class="bg-white px-4 py-3 flex items-center justify-between border border-gray-200 rounded-lg sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $transaksis->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 flex items-center space-x-1">
                            <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                            <span>Menampilkan</span>
                            <span class="font-medium">{{ $transaksis->firstItem() ?? 0 }}</span>
                            <span>sampai</span>
                            <span class="font-medium">{{ $transaksis->lastItem() ?? 0 }}</span>
                            <span>dari</span>
                            <span class="font-medium">{{ $transaksis->total() }}</span>
                            <span>hasil</span>
                        </p>
                    </div>
                    <div>
                        {{ $transaksis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
