<?php
use function Livewire\Volt\{ layout, title, state, with };
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;

layout('components.layouts.admin');
title('Transaksi - Edit');

state([
    'transaksi' => null,
    'status' => '',
    'pembayaran' => [
        'status' => '',
        'metode' => ''
    ]
]);

with(function () {
    $transaksiId = request()->route('transaksi');
    $transaksi = Transaksi::with(['pelanggan.user', 'pembayaran', 'pengiriman'])->findOrFail($transaksiId);

    return [
        'transaksi' => $transaksi,
        'status' => $transaksi->status,
        'pembayaran' => [
            'status' => $transaksi->pembayaran?->status ?? '',
            'metode' => $transaksi->pembayaran?->metode ?? ''
        ]
    ];
});

$update = function () {
    $this->transaksi->update([
        'status' => $this->status
    ]);

    if ($this->transaksi->pembayaran) {
        $this->transaksi->pembayaran->update([
            'status' => $this->pembayaran['status'],
            'metode' => $this->pembayaran['metode']
        ]);
    }

    session()->flash('message', 'Transaksi berhasil diperbarui!');
    return redirect()->route('transaksi.index');
};
?>

<div>
    <!-- Header dengan gradient background -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="pencil-square" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Edit Transaksi</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Edit status transaksi dan pembayaran
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Informasi Transaksi (Read-only) -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Transaksi</p>
                        <p class="font-mono text-sm font-medium text-gray-900">{{ $transaksi->kode_transaksi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pelanggan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->pelanggan->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Transaksi</p>
                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <form wire:submit="update" class="p-6">
                <div class="space-y-8">
                    <!-- Status Transaksi -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Transaksi</h3>
                        <div class="max-w-md">
                            <x-label for="status" value="Status Transaksi" />
                            <x-select id="status" class="mt-1 block w-full" wire:model="status">
                                <option value="PENDING">PENDING</option>
                                <option value="DIPROSES">DIPROSES</option>
                                <option value="SELESAI">SELESAI</option>
                                <option value="DIBATALKAN">DIBATALKAN</option>
                            </x-select>
                            <x-input-error for="status" class="mt-2" />
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    @if($transaksi->pembayaran)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="pembayaran.status" value="Status Pembayaran" />
                                <x-select id="pembayaran.status" class="mt-1 block w-full"
                                    wire:model="pembayaran.status">
                                    <option value="PENDING">PENDING</option>
                                    <option value="DIBAYAR">DIBAYAR</option>
                                    <option value="SELESAI">SELESAI</option>
                                    <option value="DITOLAK">DITOLAK</option>
                                </x-select>
                                <x-input-error for="pembayaran.status" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="pembayaran.metode" value="Metode Pembayaran" />
                                <x-select id="pembayaran.metode" class="mt-1 block w-full"
                                    wire:model="pembayaran.metode">
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="cash">Cash</option>
                                    <option value="e_wallet">E-Wallet</option>
                                </x-select>
                                <x-input-error for="pembayaran.metode" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <flux:icon name="exclamation-triangle" class="h-5 w-5 text-yellow-400 mr-2" />
                            <p class="text-sm text-yellow-800">Transaksi ini belum memiliki data pembayaran.</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('transaksi.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition duration-150 mr-3">
                        <flux:icon name="arrow-left" class="h-4 w-4 mr-1" />
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition duration-150">
                        <flux:icon name="check" class="h-4 w-4 mr-1" />
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
