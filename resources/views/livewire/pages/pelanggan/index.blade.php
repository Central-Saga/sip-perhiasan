<?php

use function Livewire\Volt\{state, with};
use App\Models\Pelanggan;
use Illuminate\Support\Str;

state([
    'search' => '',
    'sortField' => 'users.name',
    'sortDirection' => 'asc',
    'page' => 1
]);

with(function() {
    return [
        'pelanggans' => Pelanggan::query()
            ->join('users', 'pelanggans.user_id', '=', 'users.id')
            ->select('pelanggans.*')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('no_telepon', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
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
    Pelanggan::find($id)->delete();
};

?>

<div>    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <flux:icon name="users" class="w-8 h-8 text-indigo-600 mr-3" />
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Daftar Pelanggan
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data pelanggan perhiasan Anda</p>
            </div>
        </div>
        <a href="{{ route('pelanggan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-colors duration-200">
            <flux:icon name="plus" class="w-4 h-4 mr-2" />
            Tambah Pelanggan
        </a>
    </div><div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-input type="search" wire:model.live="search" class="pl-10" placeholder="Cari nama, email, atau no telepon..." />
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <flux:icon name="users" class="w-5 h-5" />
                            <span>Total: {{ $pelanggans->total() }} Pelanggan</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200">
                        <x-table>
                        <x-slot name="head">
                            <x-table.heading sortable wire:click="sortBy('users.name')" :direction="$sortField === 'users.name' ? $sortDirection : null">Nama</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('users.email')" :direction="$sortField === 'users.email' ? $sortDirection : null">Email</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('no_telepon')" :direction="$sortField === 'no_telepon' ? $sortDirection : null">No. Telepon</x-table.heading>
                            <x-table.heading>Alamat</x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null">Status</x-table.heading>
                            <x-table.heading>Aksi</x-table.heading>
                        </x-slot>

                        <x-slot name="body">
                            @forelse($pelanggans as $pelanggan)
                                <x-table.row wire:key="{{ $pelanggan->id }}">                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center">
                                                <flux:icon name="user" class="w-4 h-4 text-gray-600" />
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">{{ $pelanggan->user->name }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <flux:icon name="envelope" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ $pelanggan->user->email }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <flux:icon name="phone" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ $pelanggan->no_telepon }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center">
                                            <flux:icon name="map-pin" class="w-4 h-4 text-gray-400 mr-2" />
                                            {{ Str::limit($pelanggan->alamat, 50) }}
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <x-badge :type="$pelanggan->status ? 'success' : 'danger'">
                                            {{ $pelanggan->status ? 'Aktif' : 'Tidak Aktif' }}
                                        </x-badge>
                                    </x-table.cell>                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <x-button.link href="{{ route('pelanggan.edit', $pelanggan) }}" 
                                                class="flex items-center px-2 py-1 text-sm text-indigo-700 hover:text-indigo-900 rounded-md hover:bg-indigo-50" 
                                                wire:navigate>
                                                <flux:icon name="pencil" class="w-4 h-4 mr-1" />
                                                Edit
                                            </x-button.link>
                                            <button wire:click="delete({{ $pelanggan->id }})" 
                                                class="flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-900 rounded-md hover:bg-red-50">
                                                <flux:icon name="trash" class="w-4 h-4 mr-1" />
                                                Hapus
                                            </button>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.cell colspan="6">
                                        <div class="flex justify-center items-center space-x-2">
                                            <span class="font-medium py-8 text-gray-400 text-xl">Tidak ada pelanggan ditemukan...</span>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-slot>
                    </x-table>                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500 italic">
                                <flux:icon name="information-circle" class="w-4 h-4 inline-block mr-1" />
                                Menampilkan {{ $pelanggans->firstItem() ?? 0 }} hingga {{ $pelanggans->lastItem() ?? 0 }} dari {{ $pelanggans->total() }} pelanggan
                            </div>
                            <div>
                                {{ $pelanggans->links() }}
                            </div>
                        </div>
                    </div></div>
    </div></div>
