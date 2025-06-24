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
            <flux:icon name="users" class="w-8 h-8 text-indigo-600 mr-3" />            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                    Daftar Pelanggan
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola data pelanggan perhiasan Anda</p>
            </div>
        </div>
        <a href="{{ route('pelanggan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
            Tambah Pelanggan
        </a>
    </div><div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <div class="w-full sm:w-1/3 relative">                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                            </div>
                            <x-input type="search" 
                                wire:model.live="search" 
                                class="pl-10 w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm" 
                                placeholder="Cari nama, email, atau no telepon..." />
                        </div>
                        <div class="flex items-center gap-x-4">
                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                <flux:icon name="users" class="w-5 h-5 text-indigo-500 dark:text-indigo-400" />
                                <span class="font-medium">Total: {{ $pelanggans->total() }} Pelanggan</span>
                            </div>
                        </div>
                    </div>                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <x-table class="dark:divide-gray-700"><x-slot name="head">
                            <x-table.heading sortable wire:click="sortBy('users.name')" :direction="$sortField === 'users.name' ? $sortDirection : null" class="text-center">
                                <div class="flex justify-center items-center">
                                    <flux:icon name="user-circle" class="w-5 h-5 text-gray-400 mr-2" />
                                    Nama
                                </div>
                            </x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('users.email')" :direction="$sortField === 'users.email' ? $sortDirection : null" class="text-center">
                                <div class="flex justify-center items-center">
                                    <flux:icon name="envelope" class="w-5 h-5 text-gray-400 mr-2" />
                                    Email
                                </div>
                            </x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('no_telepon')" :direction="$sortField === 'no_telepon' ? $sortDirection : null" class="text-center">
                                <div class="flex justify-center items-center">
                                    <flux:icon name="phone" class="w-5 h-5 text-gray-400 mr-2" />
                                    No. Telepon
                                </div>
                            </x-table.heading>
                            <x-table.heading class="text-center">
                                <div class="flex justify-center items-center">
                                    <flux:icon name="map-pin" class="w-5 h-5 text-gray-400 mr-2" />
                                    Alamat
                                </div>
                            </x-table.heading>
                            <x-table.heading sortable wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null" class="text-center">
                                <div class="flex justify-center items-center">
                                    <flux:icon name="check-circle" class="w-5 h-5 text-gray-400 mr-2" />
                                    Status
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
                            @forelse($pelanggans as $pelanggan)                                <x-table.row wire:key="{{ $pelanggan->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                                                    <flux:icon name="user-circle" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $pelanggan->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $pelanggan->id }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                                <flux:icon name="envelope" class="w-4 h-4 text-blue-600" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $pelanggan->user->email }}</div>
                                            </div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                                                <flux:icon name="phone" class="w-4 h-4 text-green-600" />
                                            </div>
                                            <div class="font-medium text-gray-900">{{ $pelanggan->no_telepon }}</div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                                <flux:icon name="map-pin" class="w-4 h-4 text-purple-600" />
                                            </div>
                                            <div class="font-medium text-gray-900">{{ Str::limit($pelanggan->alamat, 50) }}</div>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell class="text-center">
                                        <x-badge :type="$pelanggan->status ? 'success' : 'danger'">
                                            <div class="flex items-center space-x-1">
                                                <flux:icon name="{{ $pelanggan->status ? 'check-circle' : 'x-circle' }}" class="w-4 h-4" />
                                                <span>{{ $pelanggan->status ? 'Aktif' : 'Tidak Aktif' }}</span>
                                            </div>
                                        </x-badge>
                                    </x-table.cell>
                                    <x-table.cell class="text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <x-button.link href="{{ route('pelanggan.edit', $pelanggan) }}" 
                                                class="flex items-center px-3 py-1.5 text-sm text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-150"
                                                wire:navigate>
                                                <flux:icon name="pencil-square" class="w-4 h-4 mr-1.5" />
                                                Edit
                                            </x-button.link>
                                            <button wire:click="delete({{ $pelanggan->id }})" 
                                                class="flex items-center px-3 py-1.5 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                                <flux:icon name="trash" class="w-4 h-4 mr-1.5" />
                                                Hapus
                                            </button>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @empty                                <x-table.row>
                                    <x-table.cell colspan="6">
                                        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                            <flux:icon name="users" class="w-16 h-16 mb-4" />
                                            <span class="font-medium text-xl">Tidak ada pelanggan ditemukan...</span>
                                            <p class="text-sm mt-2">Silahkan tambahkan pelanggan baru</p>
                                        </div>
                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-slot>
                    </x-table>                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500 dark:text-gray-400 italic">
                                <flux:icon name="information-circle" class="w-4 h-4 inline-block mr-1" />
                                Menampilkan {{ $pelanggans->firstItem() ?? 0 }} hingga {{ $pelanggans->lastItem() ?? 0 }} dari {{ $pelanggans->total() }} pelanggan
                            </div>
                            <div class="dark:text-gray-300">
                                {{ $pelanggans->links() }}
                            </div>
                        </div>
                    </div></div>
    </div></div>
