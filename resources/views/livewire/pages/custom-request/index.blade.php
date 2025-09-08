<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\with;
use function Livewire\Volt\usesPagination;
layout('components.layouts.admin');
title('Custom Request');
usesPagination();
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Log;

state([
    'search' => '',
    'sortField' => 'created_at',
    'sortDirection' => 'desc',
    'page' => 1,
    'showDeleteDialog' => false,
    'customRequestToDelete' => null,
    'showDetailDrawer' => false,
    'showEditDrawer' => false,
    'selectedCustomRequest' => null,
    'editStatus' => '',
    'editHarga' => '',
]);

with(function () {
    return [
        'customRequests' => CustomRequest::query()
            ->with(['pelanggan.user'])
            ->when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('kategori', 'like', '%' . $this->search . '%')
                    ->orWhere('berat', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelanggan.user', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10),
    ];
});

$openDeleteDialog = function ($customRequestId) {
    $this->customRequestToDelete = $customRequestId;
    $this->showDeleteDialog = true;
};

$closeDeleteDialog = function () {
    $this->showDeleteDialog = false;
    $this->customRequestToDelete = null;
};

$openDetailDrawer = function ($customRequestId) {
    $this->selectedCustomRequest = CustomRequest::with(['pelanggan.user'])->find($customRequestId);
    $this->showDetailDrawer = true;
};

$closeDetailDrawer = function () {
    $this->showDetailDrawer = false;
    $this->selectedCustomRequest = null;
};

$openEditDrawer = function ($customRequestId) {
    $this->selectedCustomRequest = CustomRequest::find($customRequestId);
    $this->editStatus = $this->selectedCustomRequest->status;
    $this->editHarga = $this->selectedCustomRequest->estimasi_harga;
    $this->showEditDrawer = true;
};

$closeEditDrawer = function () {
    $this->showEditDrawer = false;
    $this->selectedCustomRequest = null;
    $this->editStatus = '';
    $this->editHarga = '';
};

$updateCustomRequest = function () {
    try {
        $this->selectedCustomRequest->update([
            'status' => $this->editStatus,
            'estimasi_harga' => $this->editHarga,
        ]);

        Log::channel('custom_request_management')->info('Custom Request Updated', [
            'custom_request_id' => $this->selectedCustomRequest->id,
            'admin_id' => auth()->id(),
            'new_status' => $this->editStatus,
            'new_price' => $this->editHarga,
            'updated_at' => now()
        ]);

        session()->flash('message', 'Custom request berhasil diperbarui.');
        $this->closeEditDrawer();

    } catch (\Exception $e) {
        Log::channel('custom_request_management')->error('Custom Request Update Failed', [
            'custom_request_id' => $this->selectedCustomRequest->id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString()
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memperbarui custom request: ' . $e->getMessage());
    }
};

$delete = function ($id) {
    try {
        Log::channel('custom_request_management')->info('Custom Request Deletion Started', [
            'custom_request_id' => $id,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $customRequest = CustomRequest::find($id);

        if (!$customRequest) {
            Log::channel('custom_request_management')->warning('Custom Request Not Found for Deletion', [
                'custom_request_id' => $id
            ]);
            session()->flash('error', 'Custom request tidak ditemukan.');
            $this->closeDeleteDialog();
            return;
        }

        $requestDescription = $customRequest->deskripsi;
        $customRequest->delete();

        Log::channel('custom_request_management')->info('Custom Request Successfully Deleted', [
            'custom_request_id' => $id,
            'request_description' => $requestDescription,
            'deleted_at' => now()
        ]);

        session()->flash('message', "Custom request berhasil dihapus.");
        $this->closeDeleteDialog();

    } catch (\Exception $e) {
        Log::channel('custom_request_management')->error('Custom Request Deletion Failed', [
            'custom_request_id' => $id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString()
        ]);

        session()->flash('error', 'Terjadi kesalahan saat menghapus custom request: ' . $e->getMessage());
        $this->closeDeleteDialog();
    }
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
                    <h2 class="text-xl font-semibold text-gray-900">Data Custom Request</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola permintaan kustom perhiasan dari pelanggan
                    </p>
                </div>
            </div>
            <div class="text-sm text-gray-500">
                <flux:icon name="information-circle" class="h-4 w-4 inline mr-1" />
                Custom request dibuat dari landing page
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <flux:icon name="check-circle" class="h-5 w-5 mr-2" />
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <flux:icon name="exclamation-triangle" class="h-5 w-5 mr-2" />
        {{ session('error') }}
    </div>
    @endif

    @if (session()->has('message'))
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center">
        <flux:icon name="information-circle" class="h-5 w-5 mr-2" />
        {{ session('message') }}
    </div>
    @endif

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
                    placeholder="Cari berdasarkan deskripsi, kategori, atau pelanggan..." />
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Request</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $customRequests->total() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="cube" class="h-6 w-6 text-indigo-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ $customRequests->currentPage() }}</p>
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
                                <flux:icon name="user" class="h-4 w-4" />
                                <span>Pelanggan</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="document-text" class="h-4 w-4" />
                                <span>Deskripsi</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="check-circle" class="h-4 w-4" />
                                <span>Status</span>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="banknotes" class="h-4 w-4" />
                                <span>Harga</span>
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
                    @forelse ($customRequests as $customRequest)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center shadow-sm">
                                    <span class="text-sm font-bold text-white">
                                        {{ strtoupper(substr($customRequest->pelanggan->user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $customRequest->pelanggan->user->name
                                        }}</span>
                                    <span class="text-xs text-gray-500">{{ $customRequest->created_at->format('d/m/Y
                                        H:i') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-900 truncate" title="{{ $customRequest->deskripsi }}">
                                    {{ $customRequest->deskripsi }}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $statusColors = [
                            'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-700/10',
                            'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                            'price_proposed' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/10',
                            'approved' => 'bg-green-50 text-green-700 ring-green-700/10',
                            'rejected' => 'bg-red-50 text-red-700 ring-red-700/10',
                            'in_progress' => 'bg-orange-50 text-orange-700 ring-orange-700/10',
                            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                            'cancelled' => 'bg-gray-50 text-gray-700 ring-gray-700/10',
                            ];
                            $statusColor = $statusColors[$customRequest->status] ?? 'bg-gray-50 text-gray-700
                            ring-gray-700/10';
                            @endphp
                            <span
                                class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusColor }}">
                                <flux:icon name="check-circle" class="h-3 w-3" />
                                {{ ucfirst(str_replace('_', ' ', $customRequest->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                <flux:icon name="banknotes" class="h-3 w-3" />
                                Rp {{ number_format($customRequest->estimasi_harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="openDetailDrawer({{ $customRequest->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                    wire:navigate>
                                    <flux:icon name="eye" class="h-4 w-4 mr-1" />
                                    Detail
                                </button>
                                <button wire:click="openEditDrawer({{ $customRequest->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150">
                                    <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                    Edit
                                </button>
                                <button wire:click="openDeleteDialog({{ $customRequest->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                    <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8">
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
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada custom request</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan request baru</p>
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
                {{ $customRequests->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 flex items-center space-x-1">
                        <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                        <span>Menampilkan</span>
                        <span class="font-medium">{{ $customRequests->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-medium">{{ $customRequests->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-medium">{{ $customRequests->total() }}</span>
                        <span>hasil</span>
                    </p>
                </div>
                <div>
                    {{ $customRequests->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Simple but Beautiful Delete Modal -->
    @if($showDeleteDialog)
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;"
        wire:click="closeDeleteDialog">
        <div style="background: white; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 400px; width: 90%; margin: 20px;"
            wire:click.stop>

            <!-- Header -->
            <div
                style="background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%); padding: 24px; border-bottom: 1px solid #fecaca; border-radius: 16px 16px 0 0;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="position: relative;">
                        <div
                            style="width: 56px; height: 56px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);">
                            <flux:icon name="exclamation-triangle" style="width: 28px; height: 28px; color: white;" />
                        </div>
                        <div
                            style="position: absolute; top: -4px; right: -4px; width: 20px; height: 20px; background: #f87171; border-radius: 50%; animation: pulse 2s infinite;">
                        </div>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">Konfirmasi
                            Hapus Custom Request</h3>
                        <p style="font-size: 14px; color: #6b7280; margin: 0;">Tindakan yang tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div style="padding: 24px;">
                <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <flux:icon name="information-circle"
                            style="width: 20px; height: 20px; color: #d97706; margin-top: 2px; flex-shrink: 0;" />
                        <div>
                            <p style="color: #92400e; font-weight: 600; font-size: 14px; margin: 0 0 4px 0;">⚠️
                                Peringatan!</p>
                            <p style="color: #b45309; font-size: 14px; line-height: 1.5; margin: 0;">
                                Apakah Anda yakin ingin menghapus custom request ini? Semua data terkait akan dihapus
                                secara
                                permanen dan tidak dapat dipulihkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="padding: 0 24px 24px 24px;">
                <div style="display: flex; gap: 12px;">
                    <button type="button" wire:click="closeDeleteDialog"
                        style="flex: 1; padding: 12px 16px; font-size: 14px; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                        Batal
                    </button>
                    <button type="button" wire:click="delete({{ $customRequestToDelete }})"
                        style="flex: 1; padding: 12px 16px; font-size: 14px; font-weight: 500; color: white; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3); transition: all 0.2s;"
                        onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 10px 15px -3px rgba(239, 68, 68, 0.4)'"
                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px -1px rgba(239, 68, 68, 0.3)'">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
    @endif

    <!-- Detail Drawer -->
    <x-mary-drawer wire:model="showDetailDrawer" class="w-96" right>
        <x-slot:title>
            Detail Custom Request
        </x-slot:title>

        @if($selectedCustomRequest)
        <div class="space-y-6">
            <!-- Customer Info -->
            <x-mary-card class="bg-gradient-to-r from-blue-50 to-indigo-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="user" class="h-4 w-4" />
                    Informasi Pelanggan
                </x-slot:title>
                <p class="text-sm text-gray-600"><strong>Nama:</strong> {{
                    $selectedCustomRequest->pelanggan->user->name }}</p>
            </x-mary-card>

            <!-- Request Details -->
            <x-mary-card class="bg-gradient-to-r from-emerald-50 to-teal-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="document-text" class="h-4 w-4" />
                    Detail Permintaan
                </x-slot:title>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedCustomRequest->deskripsi }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</p>
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 mt-1">
                                <flux:icon name="tag" class="h-3 w-3" />
                                {{ $selectedCustomRequest->kategori }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Material</p>
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mt-1">
                                <flux:icon name="cube" class="h-3 w-3" />
                                {{ $selectedCustomRequest->material ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ukuran</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $selectedCustomRequest->ukuran ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Berat</p>
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10 mt-1">
                                <flux:icon name="scale" class="h-3 w-3" />
                                {{ number_format($selectedCustomRequest->berat, 2) }} gr
                            </span>
                        </div>
                    </div>
                </div>
            </x-mary-card>

            <!-- Status & Price -->
            <x-mary-card class="bg-gradient-to-r from-purple-50 to-pink-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="cog" class="h-4 w-4" />
                    Status & Harga
                </x-slot:title>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                        @php
                        $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-700/10',
                        'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                        'price_proposed' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/10',
                        'approved' => 'bg-green-50 text-green-700 ring-green-700/10',
                        'rejected' => 'bg-red-50 text-red-700 ring-red-700/10',
                        'in_progress' => 'bg-orange-50 text-orange-700 ring-orange-700/10',
                        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                        'cancelled' => 'bg-gray-50 text-gray-700 ring-gray-700/10',
                        ];
                        $statusColor = $statusColors[$selectedCustomRequest->status] ?? 'bg-gray-50 text-gray-700
                        ring-gray-700/10';
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $statusColor }} mt-1">
                            <flux:icon name="check-circle" class="h-4 w-4" />
                            {{ ucfirst(str_replace('_', ' ', $selectedCustomRequest->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Estimasi Harga</p>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-sm font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 mt-1">
                            <flux:icon name="currency-dollar" class="h-4 w-4" />
                            Rp {{ number_format($selectedCustomRequest->estimasi_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </x-mary-card>

            <!-- Timestamps -->
            <x-mary-card class="bg-gradient-to-r from-gray-50 to-slate-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="clock" class="h-4 w-4" />
                    Informasi Waktu
                </x-slot:title>
                <div class="space-y-2">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedCustomRequest->created_at->format('d/m/Y
                            H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diperbarui</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedCustomRequest->updated_at->format('d/m/Y
                            H:i') }}</p>
                    </div>
                </div>
            </x-mary-card>
        </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Tutup" wire:click="closeDetailDrawer" class="btn-ghost" />
            <x-mary-button label="Edit" wire:click="openEditDrawer({{ $selectedCustomRequest?->id }})"
                class="btn-primary" />
        </x-slot:actions>
    </x-mary-drawer>

    <!-- Edit Drawer -->
    <x-mary-drawer wire:model="showEditDrawer" class="w-96" right>
        <x-slot:title>
            Edit Custom Request
        </x-slot:title>

        @if($selectedCustomRequest)
        <div class="space-y-6">
            <!-- Status & Harga dalam satu card -->
            <x-mary-card class="bg-gradient-to-r from-blue-50 to-emerald-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="cog" class="h-4 w-4" />
                    Edit Status & Harga
                </x-slot:title>
                <div class="space-y-4 mt-3">
                    <!-- Status -->
                    <div>
                        <x-mary-select wire:model="editStatus" label="Pilih Status" placeholder="Pilih status..."
                            :options="[
                                    ['id' => 'pending', 'name' => 'Pending'],
                                    ['id' => 'reviewed', 'name' => 'Reviewed'],
                                    ['id' => 'price_proposed', 'name' => 'Price Proposed'],
                                    ['id' => 'approved', 'name' => 'Approved'],
                                    ['id' => 'rejected', 'name' => 'Rejected'],
                                    ['id' => 'in_progress', 'name' => 'In Progress'],
                                    ['id' => 'completed', 'name' => 'Completed'],
                                    ['id' => 'cancelled', 'name' => 'Cancelled']
                                ]" option-value="id" option-label="name" />
                    </div>

                    <!-- Harga -->
                    <div>
                        <x-mary-input wire:model="editHarga" type="number" label="Harga (Rupiah)"
                            placeholder="Masukkan estimasi harga..." />
                    </div>
                </div>
            </x-mary-card>

            <!-- Preview Current Data -->
            <x-mary-card class="bg-gradient-to-r from-gray-50 to-slate-50">
                <x-slot:title class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <flux:icon name="eye" class="h-4 w-4" />
                    Preview Data
                </x-slot:title>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $selectedCustomRequest->deskripsi }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</p>
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 mt-1">
                                {{ $selectedCustomRequest->kategori }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Berat</p>
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10 mt-1">
                                {{ number_format($selectedCustomRequest->berat, 2) }} gr
                            </span>
                        </div>
                    </div>
                </div>
            </x-mary-card>
        </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="closeEditDrawer" class="btn-ghost" />
            <x-mary-button label="Simpan" wire:click="updateCustomRequest" class="btn-primary" />
        </x-slot:actions>
    </x-mary-drawer>
</div>