<?php

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{state, with};

state([
    'search' => '',
    'sortField' => 'created_at',
    'sortDirection' => 'desc',
]);

with(function() {
    return [
        'pembayarans' => Pembayaran::query()
            ->when($this->search, function ($query) {
                $query->where('status', 'like', '%' . $this->search . '%')
                    ->orWhere('metode', 'like', '%' . $this->search . '%')
                    ->orWhereHas('transaksi', function ($q) {
                        $q->where('kode_transaksi', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10)
    ];
});

$delete = function($id) {
    $pembayaran = Pembayaran::findOrFail($id);
    $pembayaran->delete();
};

?>

<div class="max-w-full">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="banknotes" class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Data Pembayaran</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola pembayaran transaksi perhiasan
                    </p>
                </div>
            </div>
            <a href="{{ route('pembayaran.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                Tambah Pembayaran
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
                    class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm transition-all duration-200"
                    placeholder="Cari berdasarkan status, atau metode..." />
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                    <p class="text-2xl font-semibold text-blue-600">{{ $pembayarans->total() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <flux:icon name="credit-card" class="h-6 w-6 text-blue-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $pembayarans->currentPage() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="document-text" class="h-6 w-6 text-indigo-600" />
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
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="hashtag" class="h-4 w-4" />
                                <span>No</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="shopping-cart" class="h-4 w-4" />
                                <span>Kode Transaksi</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="calendar" class="h-4 w-4" />
                                <span>Tanggal Bayar</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="check-circle" class="h-4 w-4" />
                                <span>Status</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="credit-card" class="h-4 w-4" />
                                <span>Metode</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="currency-dollar" class="h-4 w-4" />
                                <span>Total Transaksi</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="photo" class="h-4 w-4" />
                                <span>Bukti</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 uppercase">
                            <div class="flex items-center justify-end space-x-1">
                                <flux:icon name="cog" class="h-4 w-4" />
                                <span>Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <flux:icon name="shopping-cart" class="h-4 w-4 text-blue-600" />
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $pembayaran->transaksi->kode_transaksi }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                    {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d M Y') : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-orange-100 text-orange-800' => $pembayaran->status === 'PENDING',
                                    'bg-blue-100 text-blue-800' => $pembayaran->status === 'DIBAYAR',
                                    'bg-emerald-100 text-emerald-800' => $pembayaran->status === 'SELESAI',
                                    'bg-red-100 text-red-800' => $pembayaran->status === 'DITOLAK',
                                ])>
                                    <flux:icon name="{{ 
                                        $pembayaran->status === 'SELESAI' ? 'check-circle' : 
                                        ($pembayaran->status === 'DIBAYAR' ? 'credit-card' :
                                        ($pembayaran->status === 'DITOLAK' ? 'x-circle' : 'clock'))
                                    }}" class="w-4 h-4 mr-1" />
                                    {{ $pembayaran->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                    <flux:icon name="credit-card" class="h-3 w-3" />
                                    {{ $pembayaran->metode }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10">
                                    <flux:icon name="currency-dollar" class="h-3 w-3" />
                                    Rp {{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if ($pembayaran->bukti_transfer)
                                    <a href="{{ Storage::url($pembayaran->bukti_transfer) }}" target="_blank" 
                                        class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                        <flux:icon name="photo" class="h-4 w-4 mr-1.5" />
                                        Lihat
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        <flux:icon name="x-mark" class="h-3 w-3" />
                                        Belum ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('pembayaran.edit', $pembayaran) }}" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                        wire:navigate>
                                        <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $pembayaran->id }})" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                        wire:confirm="Apakah anda yakin ingin menghapus data ini?">
                                        <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative">
                                        <div class="h-24 w-24 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-full flex items-center justify-center animate-pulse">
                                            <flux:icon name="banknotes" class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <div class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                            <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                        </div>
                                    </div>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pembayaran baru</p>
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
                {{ $pembayarans->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 flex items-center space-x-1">
                        <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                        <span>Menampilkan</span>
                        <span class="font-medium">{{ $pembayarans->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-medium">{{ $pembayarans->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-medium">{{ $pembayarans->total() }}</span>
                        <span>hasil</span>
                    </p>
                </div>
                <div>
                    {{ $pembayarans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>