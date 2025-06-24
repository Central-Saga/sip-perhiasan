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
    return match($status) {
        'selesai' => 'success',
        'diproses' => 'warning',
        'dibatalkan' => 'danger',
        default => 'default'
    };
};

?>

<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <flux:icon name="currency-dollar" class="w-8 h-8 text-indigo-600 mr-3" />
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Daftar Transaksi
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua transaksi penjualan perhiasan</p>
            </div>
        </div>
        <a href="{{ route('transaksi.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-colors duration-200">
            <flux:icon name="plus" class="w-4 h-4 mr-2" />
            Tambah Transaksi
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-input type="search" wire:model.live="search" class="pl-10" placeholder="Cari berdasarkan pelanggan atau produk..." />
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <flux:icon name="shopping-cart" class="w-5 h-5" />
                            <span>Total: {{ $transaksis->total() }} Transaksi</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200">
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
                            @forelse($transaksis as $transaksi)
                                <x-table.row wire:key="{{ $transaksi->id }}">                                    <x-table.cell>
                                        <div class="flex items-center">                                            <flux:icon name="user" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ $transaksi->pelanggan->user->name ?? 'N/A' }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <flux:icon name="sparkles" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ $transaksi->produk->nama_produk }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <flux:icon name="hashtag" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ $transaksi->jumlah }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center font-medium text-gray-900">
                                            <flux:icon name="currency-dollar" class="w-4 h-4 text-gray-400 mr-2" />
                                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <x-badge :type="$this->getStatusBadgeColor($transaksi->status)">
                                            {{ ucfirst($transaksi->status) }}
                                        </x-badge>
                                    </x-table.cell>
                                    <x-table.cell>{{ $transaksi->tanggal_transaksi->format('d M Y H:i') }}</x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-4">                                            <x-button.link href="{{ route('transaksi.edit', $transaksi) }}" 
                                                class="flex items-center px-2 py-1 text-sm text-indigo-700 hover:text-indigo-900 rounded-md hover:bg-indigo-50" 
                                                wire:navigate>
                                                <flux:icon name="pencil" class="w-4 h-4 mr-1" />
                                                Edit
                                            </x-button.link>
                                            <button wire:click="delete({{ $transaksi->id }})" 
                                                class="flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-900 rounded-md hover:bg-red-50">
                                                <flux:icon name="trash" class="w-4 h-4 mr-1" />
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
