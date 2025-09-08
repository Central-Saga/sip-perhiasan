<?php
use function Livewire\Volt\{ layout, title, usesPagination, state, with };
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
layout('components.layouts.admin');
title('Transaksi');
usesPagination();

state([
    'search' => '',
    'sortField' => 'created_at',
    'sortDirection' => 'desc',
    'page' => 1,
    'showDetailDrawer' => false,
    'selectedTransaksi' => null,
]);

with(function () {
    return [
        'transaksis' => Transaksi::query()
            ->with(['pelanggan.user', 'detailTransaksi.produk', 'pembayaran', 'pengiriman'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_transaksi', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelanggan.user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('detailTransaksi.produk', function ($p) {
                        $p->where('nama_produk', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10),
    ];
});

$openDetailDrawer = function ($transaksiId) {
    $this->selectedTransaksi = Transaksi::with([
        'pelanggan.user',
        'detailTransaksi.produk',
        'detailTransaksi.customRequest',
        'pembayaran',
        'pengiriman'
    ])->find($transaksiId);
    $this->showDetailDrawer = true;
};

$closeDetailDrawer = function () {
    $this->showDetailDrawer = false;
    $this->selectedTransaksi = null;
};

$delete = function ($id) {
    Transaksi::find($id)?->delete();
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
                <input wire:model.live="search" type="search"
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

    <!-- Enhanced Compact Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transaksi</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status & Pembayaran</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <!-- Kode Transaksi & Tanggal -->
                        <td class="px-3 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-8 w-8 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <flux:icon name="shopping-cart" class="h-4 w-4 text-indigo-600" />
                                    </div>
                                    <div>
                                        <div class="font-mono text-sm font-medium text-gray-900">{{
                                            $transaksi->kode_transaksi }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaksi->created_at?->format('d M Y
                                            H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $transaksi->detailTransaksi->count() }} item
                                </div>
                            </div>
                        </td>

                        <!-- Pelanggan -->
                        <td class="px-3 py-4">
                            <div class="space-y-1">
                                <div class="font-medium text-gray-900">{{ $transaksi->pelanggan->user->name ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $transaksi->pelanggan->user->email ?? '-' }}</div>
                            </div>
                        </td>

                        <!-- Status & Pembayaran -->
                        <td class="px-3 py-4">
                            <div class="space-y-2">
                                <!-- Status Transaksi -->
                                @php
                                $statusConfig = [
                                'PENDING' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'ring' =>
                                'ring-yellow-700/10', 'icon' => 'clock'],
                                'DIPROSES' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'ring' =>
                                'ring-blue-700/10', 'icon' => 'cog-6-tooth'],
                                'SELESAI' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' =>
                                'ring-emerald-700/10', 'icon' => 'check-circle'],
                                'DIBATALKAN' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'ring' =>
                                'ring-red-700/10', 'icon' => 'x-circle']
                                ];
                                $config = $statusConfig[$transaksi->status] ?? $statusConfig['PENDING'];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $config['bg'] }} {{ $config['text'] }} {{ $config['ring'] }}">
                                    <flux:icon name="{{ $config['icon'] }}" class="h-3 w-3" />
                                    {{ $transaksi->status }}
                                </span>

                                <!-- Status Pembayaran -->
                                @if($transaksi->pembayaran)
                                @php
                                $pembayaranConfig = [
                                'PENDING' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'icon' => 'clock'],
                                'DIBAYAR' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'credit-card'],
                                'SELESAI' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' =>
                                'check-circle'],
                                'DITOLAK' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'x-circle']
                                ];
                                $pembayaranStatus = $pembayaranConfig[$transaksi->pembayaran->status] ??
                                $pembayaranConfig['PENDING'];
                                @endphp
                                <div
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium {{ $pembayaranStatus['bg'] }} {{ $pembayaranStatus['text'] }}">
                                    <flux:icon name="{{ $pembayaranStatus['icon'] }}" class="h-3 w-3" />
                                    {{ $transaksi->pembayaran->status }}
                                </div>
                                @else
                                <div
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium bg-gray-50 text-gray-600">
                                    <flux:icon name="exclamation-triangle" class="h-3 w-3" />
                                    Belum Bayar
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-3 py-4">
                            <div class="text-right">
                                <div class="text-lg font-semibold text-gray-900">Rp {{
                                    number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                @if($transaksi->pembayaran && $transaksi->pembayaran->metode)
                                <div class="text-xs text-gray-500">{{ ucfirst($transaksi->pembayaran->metode) }}</div>
                                @endif
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="px-3 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1">
                                <button wire:click="openDetailDrawer({{ $transaksi->id }})"
                                    class="inline-flex items-center px-2 py-1.5 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150 text-xs"
                                    title="Detail">
                                    <flux:icon name="eye" class="h-4 w-4" />
                                </button>
                                <a href="{{ route('transaksi.edit', $transaksi) }}"
                                    class="inline-flex items-center px-2 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150 text-xs"
                                    wire:navigate title="Edit">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </a>
                                <button wire:click="delete({{ $transaksi->id }})"
                                    class="inline-flex items-center px-2 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150 text-xs"
                                    wire:confirm="Apakah anda yakin ingin menghapus data ini?" title="Hapus">
                                    <flux:icon name="trash" class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8">
                            <div class="flex flex-col items-center justify-center">
                                <div class="relative">
                                    <div
                                        class="h-24 w-24 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse">
                                        <flux:icon name="shopping-cart" class="h-12 w-12 text-gray-400" />
                                    </div>
                                    <div
                                        class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
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

    <!-- Detail Drawer -->
    <x-mary-drawer wire:model="showDetailDrawer" class="w-[800px]" right>
        <x-slot:title>
            Detail Transaksi - {{ $selectedTransaksi?->kode_transaksi ?? '' }}
        </x-slot:title>

        @if($selectedTransaksi)
        <div class="space-y-6">
            <!-- Header dengan Icon -->
            <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <flux:icon name="shopping-cart" class="h-6 w-6 text-indigo-600" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi</h3>
                    <p class="text-sm text-gray-500">{{ $selectedTransaksi->kode_transaksi }}</p>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <x-mary-card class="bg-gradient-to-r from-indigo-50 to-purple-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="information-circle" class="h-4 w-4" />
                    Informasi Transaksi
                </x-slot:title>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kode Transaksi</p>
                        <p class="text-sm font-mono text-gray-900 mt-1">{{ $selectedTransaksi->kode_transaksi }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedTransaksi->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                        @php
                        $statusConfig = [
                        'PENDING' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'ring' =>
                        'ring-yellow-700/10', 'icon' => 'clock'],
                        'DIPROSES' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'ring' => 'ring-blue-700/10',
                        'icon' => 'cog-6-tooth'],
                        'SELESAI' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' =>
                        'ring-emerald-700/10', 'icon' => 'check-circle'],
                        'DIBATALKAN' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'ring' => 'ring-red-700/10',
                        'icon' => 'x-circle']
                        ];
                        $config = $statusConfig[$selectedTransaksi->status] ?? $statusConfig['PENDING'];
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $config['bg'] }} {{ $config['text'] }} {{ $config['ring'] }} mt-1">
                            <flux:icon name="{{ $config['icon'] }}" class="h-3 w-3" />
                            {{ $selectedTransaksi->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipe Pesanan</p>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 mt-1">
                            <flux:icon name="sparkles" class="h-3 w-3" />
                            {{ ucfirst($selectedTransaksi->tipe_pesanan) }}
                        </span>
                    </div>
                </div>
            </x-mary-card>

            <!-- Informasi Pelanggan -->
            <x-mary-card class="bg-gradient-to-r from-blue-50 to-cyan-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="user" class="h-4 w-4" />
                    Informasi Pelanggan
                </x-slot:title>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedTransaksi->pelanggan->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedTransaksi->pelanggan->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">No. Telepon</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedTransaksi->pelanggan->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Pelanggan</p>
                        <span
                            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium {{ $selectedTransaksi->pelanggan->status === 'Aktif' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} mt-1">
                            <flux:icon
                                name="{{ $selectedTransaksi->pelanggan->status === 'Aktif' ? 'check-circle' : 'x-circle' }}"
                                class="h-3 w-3" />
                            {{ $selectedTransaksi->pelanggan->status }}
                        </span>
                    </div>
                </div>
            </x-mary-card>

            <!-- Detail Produk -->
            <x-mary-card class="bg-gradient-to-r from-emerald-50 to-teal-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="cube" class="h-4 w-4" />
                    Detail Produk ({{ $selectedTransaksi->detailTransaksi->count() }} item)
                </x-slot:title>
                <div class="space-y-3">
                    @foreach($selectedTransaksi->detailTransaksi as $detail)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                @if($detail->produk)
                                <h4 class="font-medium text-gray-900">{{ $detail->produk->nama_produk }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $detail->produk->deskripsi }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                        <flux:icon name="tag" class="h-3 w-3" />
                                        {{ $detail->produk->kategori }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                        <flux:icon name="cube" class="h-3 w-3" />
                                        {{ $detail->jumlah }} unit
                                    </span>
                                </div>
                                @elseif($detail->customRequest)
                                <h4 class="font-medium text-gray-900">Custom Request</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $detail->customRequest->deskripsi }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700">
                                        <flux:icon name="sparkles" class="h-3 w-3" />
                                        Custom
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                        <flux:icon name="cube" class="h-3 w-3" />
                                        {{ $detail->jumlah }} unit
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($detail->sub_total,
                                    0, ',', '.') }}</p>
                                @if($detail->produk)
                                <p class="text-xs text-gray-500">@ Rp {{ number_format($detail->produk->harga, 0, ',',
                                    '.') }}/unit</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-mary-card>

            <!-- Informasi Pembayaran -->
            @if($selectedTransaksi->pembayaran)
            <x-mary-card class="bg-gradient-to-r from-yellow-50 to-orange-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="credit-card" class="h-4 w-4" />
                    Informasi Pembayaran
                </x-slot:title>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Pembayaran</p>
                        @php
                        $pembayaranConfig = [
                        'PENDING' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'icon' => 'clock'],
                        'DIBAYAR' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'credit-card'],
                        'SELESAI' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'check-circle'],
                        'DITOLAK' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'x-circle']
                        ];
                        $pembayaranStatus = $pembayaranConfig[$selectedTransaksi->pembayaran->status] ??
                        $pembayaranConfig['PENDING'];
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium {{ $pembayaranStatus['bg'] }} {{ $pembayaranStatus['text'] }} mt-1">
                            <flux:icon name="{{ $pembayaranStatus['icon'] }}" class="h-3 w-3" />
                            {{ $selectedTransaksi->pembayaran->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Metode Pembayaran</p>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 mt-1">
                            <flux:icon name="credit-card" class="h-3 w-3" />
                            {{ ucfirst($selectedTransaksi->pembayaran->metode) }}
                        </span>
                    </div>
                    @if($selectedTransaksi->pembayaran->tanggal_bayar)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Bayar</p>
                        <p class="text-sm text-gray-900 mt-1">{{
                            $selectedTransaksi->pembayaran->tanggal_bayar->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    @if($selectedTransaksi->pembayaran->bukti_transfer)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bukti Transfer</p>
                        <a href="{{ Storage::url($selectedTransaksi->pembayaran->bukti_transfer) }}" target="_blank"
                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <flux:icon name="photo" class="h-4 w-4" />
                            Lihat Bukti
                        </a>
                    </div>
                    @endif
                </div>
            </x-mary-card>
            @endif

            <!-- Informasi Pengiriman -->
            @if($selectedTransaksi->pengiriman)
            <x-mary-card class="bg-gradient-to-r from-purple-50 to-pink-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="truck" class="h-4 w-4" />
                    Informasi Pengiriman
                </x-slot:title>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Pengiriman</p>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 mt-1">
                            <flux:icon name="truck" class="h-3 w-3" />
                            {{ $selectedTransaksi->pengiriman->status }}
                        </span>
                    </div>
                    @if($selectedTransaksi->pengiriman->tanggal_pengiriman)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Pengiriman</p>
                        <p class="text-sm text-gray-900 mt-1">{{
                            $selectedTransaksi->pengiriman->tanggal_pengiriman->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    @if($selectedTransaksi->pengiriman->deskripsi)
                    <div class="col-span-2">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedTransaksi->pengiriman->deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </x-mary-card>
            @endif

            <!-- Total Harga -->
            <x-mary-card class="bg-gradient-to-r from-green-50 to-emerald-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="currency-dollar" class="h-4 w-4" />
                    Ringkasan Harga
                </x-slot:title>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900">Rp {{ number_format($selectedTransaksi->total_harga,
                        0, ',', '.') }}</div>
                    <p class="text-sm text-gray-600 mt-1">Total Pembayaran</p>
                </div>
            </x-mary-card>
        </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Tutup" wire:click="closeDetailDrawer" class="btn-ghost" />
            @if($selectedTransaksi)
            <x-mary-button label="Edit" href="{{ route('transaksi.edit', $selectedTransaksi) }}" class="btn-primary" />
            @endif
        </x-slot:actions>
    </x-mary-drawer>
</div>
</div>
