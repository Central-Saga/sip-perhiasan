<?php

use function Livewire\Volt\{state, with};
use App\Models\Produk;
use Illuminate\Support\Str;

state([
    'search' => '',
    'sortField' => 'nama_produk',
    'sortDirection' => 'asc',
    'page' => 1
]);

with(function() {
    return [
        'produks' => Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%')
                    ->orWhere('kategori', 'like', '%' . $this->search . '%');
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
    Produk::find($id)->delete();
};

?>

<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <flux:icon name="sparkles" class="w-8 h-8 text-indigo-600 mr-3" />
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Daftar Produk
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola katalog perhiasan Anda</p>
            </div>
        </div>
        <a href="{{ route('produk.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-colors duration-200">
            <flux:icon name="plus" class="w-4 h-4 mr-2" />
            Tambah Produk
        </a>
    </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">                <div class="flex justify-between items-center mb-6">
                    <div class="w-1/3 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                        </div>
                        <x-input type="search" wire:model.live="search" class="pl-10" placeholder="Cari nama atau kategori produk..." />
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <flux:icon name="cube" class="w-5 h-5" />
                        <span>Total: {{ $produks->total() }} Produk</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('nama_produk')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Nama Produk
                                </th>
                                <th wire:click="sortBy('kategori')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Kategori
                                </th>
                                <th wire:click="sortBy('harga')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Harga
                                </th>
                                <th wire:click="sortBy('stok')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Stok
                                </th>
                                <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($produks as $produk)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($produk->foto)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_produk }}">
                                        </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $produk->nama_produk }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $produk->kategori }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $produk->stok }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produk->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $produk->status ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('produk.edit', $produk) }}" 
                                            class="flex items-center px-2 py-1 text-sm text-indigo-700 hover:text-indigo-900 rounded-md hover:bg-indigo-50"
                                            wire:navigate>
                                            <flux:icon name="pencil" class="w-4 h-4 mr-1" />
                                            Edit
                                        </a>
                                        <button wire:click="delete({{ $produk->id }})" 
                                            class="flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-900 rounded-md hover:bg-red-50"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <flux:icon name="trash" class="w-4 h-4 mr-1" />
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $produks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
