<?php

use function Livewire\Volt\{
    layout, title, state, mount, with,
    updated, usesPagination, computed
};

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Str;

layout('components.layouts.admin');
title('Pelanggan Management');
usesPagination();

// Functional API state
state([
    'search' => '',
    'sortField' => 'id',
    'sortDirection' => 'asc',
    'page' => 1,
]);

// Computed property: pelanggans
$pelanggans = computed(function () {
    return Pelanggan::query()
        ->with('user') // Use eager loading instead of join
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
        ->when($this->sortField === 'name', function ($query) {
            $query->orderBy(User::select('name')->whereColumn('users.id', 'pelanggan.user_id'), $this->sortDirection);
        })
        ->when($this->sortField === 'email', function ($query) {
            $query->orderBy(User::select('email')->whereColumn('users.id', 'pelanggan.user_id'), $this->sortDirection);
        })
        ->when(!in_array($this->sortField, ['name', 'email']), function ($query) {
            $query->orderBy($this->sortField, $this->sortDirection);
        })
        ->paginate(10);
})->persist();

// Action: delete
$delete = function ($id) {
    Pelanggan::find($id)?->delete();
};

// Action: sort
$sortBy = function ($field) {
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
};
?>




<div>
    <div class="max-w-full">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm">
                        <flux:icon name="users" class="h-8 w-8 text-indigo-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Data Pelanggan</h2>
                        <p class="mt-1 text-sm text-gray-600 flex items-center">
                            <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                            Kelola data pelanggan perhiasan Anda
                        </p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                    <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                    Tambah Pelanggan
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
                    <input wire:model.live="search" type="search"
                        class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all duration-200"
                        placeholder="Cari nama, email, no telepon, atau alamat..." />
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pelanggan</p>
                        <p class="text-2xl font-semibold text-indigo-600">{{ $this->pelanggans->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <flux:icon name="users" class="h-6 w-6 text-indigo-600" />
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Halaman</p>
                        <p class="text-2xl font-semibold text-purple-600">{{ $this->pelanggans->currentPage() }}</p>
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
                            <th
                                class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="hashtag" class="h-4 w-4" />
                                    <span>No</span>
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                wire:click="sortBy('name')">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="user-circle" class="h-4 w-4" />
                                    <span>Nama</span>
                                    @if($sortField === 'name')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                        class="h-4 w-4" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                wire:click="sortBy('email')">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="envelope" class="h-4 w-4" />
                                    <span>Email</span>
                                    @if($sortField === 'email')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                        class="h-4 w-4" />
                                    @endif
                                </div>
                            </th>
                            <th
                                class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="phone" class="h-4 w-4" />
                                    <span>No. Telepon</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="map-pin" class="h-4 w-4" />
                                    <span>Alamat</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <flux:icon name="check-circle" class="h-4 w-4" />
                                    <span>Status</span>
                                </div>
                            </th>
                            <th
                                class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-1">
                                    <flux:icon name="cog" class="h-4 w-4" />
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->pelanggans as $index => $pelanggan)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ ($this->pelanggans->currentPage()
                                - 1) * $this->pelanggans->perPage() + $index + 1 }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium text-indigo-700">
                                            {{ $pelanggan->user ? strtoupper(substr($pelanggan->user->name, 0, 2)) :
                                            'NA' }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $pelanggan->user ? $pelanggan->user->name
                                        : 'Nama tidak tersedia' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    <flux:icon name="envelope" class="h-3 w-3" />
                                    {{ $pelanggan->user ? $pelanggan->user->email : 'Email tidak tersedia' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                    <flux:icon name="phone" class="h-3 w-3" />
                                    {{ $pelanggan->no_telepon }}
                                </span>
                            </td>
                            <td class="px-4 py-4 max-w-xs">
                                <div class="relative group">
                                    <p class="text-sm text-gray-900 truncate">
                                        {{ Str::limit($pelanggan->alamat, 50) }}
                                    </p>
                                    <div
                                        class="hidden group-hover:block absolute z-10 p-2 bg-gray-900 text-white text-xs rounded-lg mt-1 max-w-xs">
                                        {{ $pelanggan->alamat }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                    :class="$pelanggan->status ? 'bg-emerald-50 text-emerald-700 ring-emerald-700/10' : 'bg-red-50 text-red-700 ring-red-700/10'">
                                    <flux:icon name="{{ $pelanggan->status ? 'check-circle' : 'x-circle' }}"
                                        class="h-3 w-3" />
                                    {{ $pelanggan->status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('pelanggan.edit', $pelanggan) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150"
                                        wire:navigate>
                                        <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $pelanggan->id }})"
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
                            <td colspan="7" class="px-4 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative">
                                        <div
                                            class="h-24 w-24 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse">
                                            <flux:icon name="users" class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <div
                                            class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                            <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                        </div>
                                    </div>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada pelanggan</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pelanggan baru</p>
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
                    {{ $this->pelanggans->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 flex items-center space-x-1">
                            <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                            <span>Menampilkan</span>
                            <span class="font-medium">{{ $this->pelanggans->firstItem() ?? 0 }}</span>
                            <span>sampai</span>
                            <span class="font-medium">{{ $this->pelanggans->lastItem() ?? 0 }}</span>
                            <span>dari</span>
                            <span class="font-medium">{{ $this->pelanggans->total() }}</span>
                            <span>hasil</span>
                        </p>
                    </div>
                    <div>
                        {{ $this->pelanggans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>