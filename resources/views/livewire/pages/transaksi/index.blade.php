<?php

use function Livewire\Volt\{state, with};
use App\Models\Transaksi;
use Illuminate\Support\Str;

state([
    'search' => '',
    'sortField' => 'tanggal_transaksi',
    'sortDirection' => 'desc',
    'page' => 1
]);

with(function() {    return [
        'transaksis' => Transaksi::query()
            ->with(['pelanggan.user', 'produk']) // eager load relations
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
});

$sortBy = function($field) {
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
};

$delete = function($id) {
    Transaksi::find($id)->delete();
};

$getStatusBadgeColor = function($status) {
    return match(strtolower($status)) {
        'selesai', 'completed' => 'success',
        'diproses', 'processing', 'pending' => 'warning',
        'dibatalkan', 'cancelled' => 'danger',
        default => 'default'
    };
};

$getStatusIcon = function($status) {
    return match(strtolower($status)) {
        'selesai', 'completed' => 'check-circle',
        'diproses', 'processing' => 'arrow-path',
        'pending' => 'clock',
        'dibatalkan', 'cancelled' => 'x-circle',
        default => 'question-mark-circle'
    };
};

?>

<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">            <flux:icon name="currency-dollar" class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mr-3" />
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Daftar Transaksi
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola semua transaksi penjualan perhiasan</p>
            </div>
        </div> 
        <a href="{{ route('transaksi.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
            Tambah Transaksi
        </a>
    </div>    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <div class="w-full sm:w-1/3 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                            </div>
                            <x-input type="search" 
                                wire:model.live="search" 
                                class="pl-10 w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm" 
                                placeholder="Cari berdasarkan pelanggan atau produk..." />
                        </div>
                        <div class="flex items-center gap-x-4">
                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                <flux:icon name="shopping-cart" class="w-5 h-5 text-indigo-500 dark:text-indigo-400" />
                                <span class="font-medium">Total: {{ $transaksis->total() }} Transaksi</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-table>
                        <x-slot name="head">                            <x-table.heading sortable wire:click="sortBy('pelanggan_id')" :direction="$sortField === 'pelanggan_id' ? $sortDirection : null">Pelanggan</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('produk_id')" :direction="$sortField === 'produk_id' ? $sortDirection : null">Produk</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('jumlah')" :direction="$sortField === 'jumlah' ? $sortDirection : null">Jumlah</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('total_harga')" :direction="$sortField === 'total_harga' ? $sortDirection : null">Total Harga</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null">Status</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('tanggal_transaksi')" :direction="$sortField === 'tanggal_transaksi' ? $sortDirection : null">Tanggal</x-table.heading>
                            <x-table.heading>Aksi</x-table.heading>
                        </x-slot>

                        <x-slot name="body">
                            @forelse($transaksis as $transaksi)                                <x-table.row wire:key="{{ $transaksi->id }}" class="hover:bg-gray-50">
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center">
                                                    <flux:icon name="user" class="w-4 h-4 text-indigo-600" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $transaksi->pelanggan->user->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $transaksi->pelanggan_id }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                                    <flux:icon name="sparkles" class="w-4 h-4 text-purple-600" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $transaksi->produk->nama_produk }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $transaksi->produk_id }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                                <flux:icon name="hashtag" class="w-4 h-4 text-blue-600" />
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $transaksi->jumlah }} unit</span>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                                                <flux:icon name="banknotes" class="w-4 h-4 text-green-600" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                                <div class="text-sm text-gray-500">Per unit: Rp {{ number_format($transaksi->total_harga / $transaksi->jumlah, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-2">
                                            <x-badge :type="$this->getStatusBadgeColor($transaksi->status)">
                                                <div class="flex items-center space-x-1">
                                                    <flux:icon :name="$this->getStatusIcon($transaksi->status)" class="w-4 h-4" />
                                                    <span>{{ ucfirst($transaksi->status) }}</span>
                                                </div>
                                            </x-badge>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                                <flux:icon name="calendar" class="w-4 h-4 text-gray-600" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $transaksi->tanggal_transaksi->format('d M Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaksi->tanggal_transaksi->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>                                    <x-table.cell>
                                        <div class="flex items-center justify-end space-x-3">
                                            <x-button.link href="{{ route('transaksi.edit', $transaksi) }}" 
                                                class="flex items-center px-3 py-1.5 text-sm text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-150" 
                                                wire:navigate>
                                                <flux:icon name="pencil-square" class="w-4 h-4 mr-1.5" />
                                                Edit
                                            </x-button.link>
                                            <button wire:click="delete({{ $transaksi->id }})" 
                                                class="flex items-center px-3 py-1.5 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                <flux:icon name="trash" class="w-4 h-4 mr-1.5" />
                                                Hapus
                                            </button>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="7">
                                        <div class="flex justify-center items-center space-x-2">
                                            <span class="font-medium py-8 text-gray-400 text-xl">Tidak ada transaksi ditemukan...</span>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-slot>
                    </x-table>

                    <div class="mt-4">
                        {{ $transaksis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div></div>
