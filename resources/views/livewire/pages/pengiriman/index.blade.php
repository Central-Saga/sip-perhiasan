<?php

use App\Models\Pengiriman;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{state, with};

state([
    'search' => '',
    'sortField' => 'created_at',
    'sortDirection' => 'desc',
    'page' => 1
]);

with(function() {
    return [
        'pengirimans' => Pengiriman::query()
            ->when($this->search, function ($query) {
                $query->where('kode_pengiriman', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('kurir', 'like', '%' . $this->search . '%')
                    ->orWhere('no_resi', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
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
    $pengiriman = Pengiriman::findOrFail($id);
    $pengiriman->delete();
};

?>

<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-x-3">
                    <flux:icon name="paper-airplane" class="w-8 h-8 text-indigo-600" />
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Data Pengiriman</h1>
                        <p class="mt-2 text-sm text-gray-700">Kelola pengiriman pesanan perhiasan</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('pengiriman.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
                    Tambah Pengiriman
                </a>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <x-input type="search" 
                    wire:model.live="search" 
                    class="pl-10 w-full" 
                    placeholder="Cari kode pengiriman, status, atau kurir..." />
            </div>
            <div class="flex items-center gap-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                    <flux:icon name="truck" class="w-5 h-5 text-indigo-500" />
                    <span class="font-medium">Total: {{ $pengirimans->total() }} Pengiriman</span>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white/50 backdrop-blur-xl rounded-lg border border-gray-200">
            <x-table>
                <x-slot name="head">
                    <x-table.heading sortable wire:click="sortBy('kode_pengiriman')" :direction="$sortField === 'kode_pengiriman' ? $sortDirection : null" class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="identification" class="w-5 h-5 text-gray-400 mr-2" />
                            Kode
                        </div>
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('transaksi_id')" :direction="$sortField === 'transaksi_id' ? $sortDirection : null" class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="shopping-cart" class="w-5 h-5 text-gray-400 mr-2" />
                            Transaksi
                        </div>
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null" class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="check-circle" class="w-5 h-5 text-gray-400 mr-2" />
                            Status
                        </div>
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('kurir')" :direction="$sortField === 'kurir' ? $sortDirection : null" class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="truck" class="w-5 h-5 text-gray-400 mr-2" />
                            Kurir
                        </div>
                    </x-table.heading>
                    <x-table.heading class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="document-text" class="w-5 h-5 text-gray-400 mr-2" />
                            No. Resi
                        </div>
                    </x-table.heading>
                    <x-table.heading class="text-center">
                        <div class="flex justify-center items-center">
                            <flux:icon name="map-pin" class="w-5 h-5 text-gray-400 mr-2" />
                            Alamat
                        </div>
                    </x-table.heading>
                    <x-table.heading class="text-center relative">
                        <div class="flex justify-center items-center px-10">
                            <flux:icon name="cog" class="w-5 h-5 text-gray-400 mr-2" />
                            Aksi
                        </div>
                    </x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($pengirimans as $pengiriman)
                        <x-table.row wire:key="{{ $pengiriman->id }}" class="hover:bg-gray-50">
                            <x-table.cell>
                                <div class="font-medium text-gray-900">{{ $pengiriman->kode_pengiriman }}</div>
                            </x-table.cell>
                            <x-table.cell>
                                <div class="font-medium text-gray-900">{{ $pengiriman->transaksi->kode_transaksi }}</div>
                            </x-table.cell>
                            <x-table.cell>
                                <x-badge :type="$pengiriman->status === 'SELESAI' ? 'success' : ($pengiriman->status === 'DIKIRIM' ? 'info' : 'warning')">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="{{ 
                                            $pengiriman->status === 'SELESAI' ? 'check-circle' : 
                                            ($pengiriman->status === 'DIKIRIM' ? 'paper-airplane' : 'clock') 
                                        }}" class="w-4 h-4" />
                                        <span>{{ $pengiriman->status }}</span>
                                    </div>
                                </x-badge>
                            </x-table.cell>
                            <x-table.cell>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                        <flux:icon name="truck" class="w-4 h-4 text-blue-600" />
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $pengiriman->kurir }}</div>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                <div class="font-medium text-gray-900">{{ $pengiriman->no_resi ?? '-' }}</div>
                            </x-table.cell>
                            <x-table.cell>
                                <div class="font-medium text-gray-900 max-w-xs truncate">{{ $pengiriman->alamat }}</div>
                            </x-table.cell>
                            <x-table.cell class="text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <x-button.link href="{{ route('pengiriman.edit', $pengiriman) }}" 
                                        class="flex items-center px-3 py-1.5 text-sm text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-150"
                                        wire:navigate>
                                        <flux:icon name="pencil-square" class="w-4 h-4 mr-1.5" />
                                        Edit
                                    </x-button.link>
                                    <button wire:click="delete({{ $pengiriman->id }})" 
                                        class="flex items-center px-3 py-1.5 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengiriman ini?')">
                                        <flux:icon name="trash" class="w-4 h-4 mr-1.5" />
                                        Hapus
                                    </button>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="7">
                                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                    <flux:icon name="inbox" class="w-16 h-16 mb-4" />
                                    <span class="font-medium text-xl">Tidak ada pengiriman ditemukan...</span>
                                    <p class="text-sm mt-2">Silahkan tambahkan pengiriman baru</p>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500 italic">
                    <flux:icon name="information-circle" class="w-4 h-4 inline-block mr-1" />
                    Menampilkan {{ $pengirimans->firstItem() ?? 0 }} hingga {{ $pengirimans->lastItem() ?? 0 }} dari {{ $pengirimans->total() }} pengiriman
                </div>
                <div>
                    {{ $pengirimans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>