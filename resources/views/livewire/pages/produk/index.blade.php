<?php
use Livewire\Volt\Component;
use function Livewire\Volt\{ layout, title, state, with, usesPagination };
layout('components.layouts.admin');
title('Produk');
usesPagination();
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

state([
    'search' => '',
    'sortField' => 'nama_produk',
    'sortDirection' => 'asc',
    'page' => 1,
]);

with(function () {
    return [
        'produks' => Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10),
    ];
});

$delete = function ($id) {
    Produk::find($id)?->delete();
};
?>

<div class="max-w-full">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="sparkles" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Data Produk</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola katalog produk perhiasan Anda
                    </p>
                </div>
            </div>
            <a href="{{ route('produk.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                Tambah Produk
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
                    placeholder="Cari nama atau kategori produk..." />
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Produk</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $produks->total() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="cube" class="h-6 w-6 text-indigo-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ $produks->currentPage() }}</p>
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
                                <flux:icon name="hashtag" class="h-4 w-4" />
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="sparkles" class="h-4 w-4" />
                                <span>Foto & Nama Produk</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="tag" class="h-4 w-4" />
                                <span>Kategori</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="banknotes" class="h-4 w-4" />
                                <span>Harga</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="cube" class="h-4 w-4" />
                                <span>Stok</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="check-circle" class="h-4 w-4" />
                                <span>Status</span>
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
                    @forelse ($produks as $produk)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                @if($produk->foto)
                                <div class="relative group">

                                    <img src="{{ asset('storage/' . $produk->foto) }}"
                                        class="h-16 w-16 rounded-lg object-cover cursor-pointer"
                                        alt="{{ $produk->nama_produk }}"
                                        onclick="openImageModal('{{ asset('storage/' . $produk->foto) }}', '{{ $produk->nama_produk }}')">
                                </div>
                                @else
                                <div
                                    class="h-16 w-16 flex-shrink-0 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center border-2 border-dashed border-purple-200">
                                    <flux:icon name="sparkles" class="h-6 w-6 text-purple-600" />
                                </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $produk->nama_produk }}</span>
                                    <span class="text-xs text-gray-500">ID: {{ $produk->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                <flux:icon name="tag" class="h-3 w-3" />
                                {{ $produk->kategori }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                <flux:icon name="banknotes" class="h-3 w-3" />
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                <flux:icon name="cube" class="h-3 w-3" />
                                {{ $produk->stok }} unit
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($produk->status)
                            <span
                                class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset bg-emerald-50 text-emerald-700 ring-emerald-700/10">
                                <flux:icon name="check-circle" class="h-3 w-3" />
                                Aktif
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset bg-red-50 text-red-700 ring-red-700/10">
                                <flux:icon name="x-circle" class="h-3 w-3" />
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('produk.edit', $produk) }}"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150"
                                    wire:navigate>
                                    <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                    Edit
                                </a>
                                <button wire:click="delete({{ $produk->id }})"
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
                                        <flux:icon name="sparkles" class="h-12 w-12 text-gray-400" />
                                    </div>
                                    <div
                                        class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                        <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                    </div>
                                </div>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada produk</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan produk baru</p>
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
                {{ $produks->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 flex items-center space-x-1">
                        <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                        <span>Menampilkan</span>
                        <span class="font-medium">{{ $produks->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-medium">{{ $produks->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-medium">{{ $produks->total() }}</span>
                        <span>hasil</span>
                    </p>
                </div>
                <div>
                    {{ $produks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                    <button onclick="closeImageModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <flux:icon name="x-mark" class="h-6 w-6" />
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-96 object-contain rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Image Modal -->
    <script>
        function openImageModal(imageSrc, productName) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = productName;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</div>