<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

@php
    $pembayaran = \App\Models\Pembayaran::with('transaksi')->findOrFail(request()->route('pembayaran'));
    $transaksis = \App\Models\Transaksi::all();
@endphp

<div>
    <div class="max-w-7xl mx-auto mt-10">
        <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Edit Pembayaran</h2>
            <p class="text-gray-500 mb-6">Silakan ubah data pembayaran sesuai kebutuhan di bawah ini.</p>
            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kiri: Form Data Utama -->
                    <div class="md:col-span-2 grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="transaksi_id" value="Transaksi" />
                            <x-select id="transaksi_id" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50" wire:model="transaksi_id">
                                <option value="">Pilih Transaksi</option>
                                @foreach($transaksis as $transaksi)
                                    <option value="{{ $transaksi->id }}" @if($pembayaran && $pembayaran->transaksi_id == $transaksi->id) selected @endif>
                                        {{ $transaksi->kode_transaksi }} - Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('transaksi_id')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="tanggal_bayar" value="Tanggal Pembayaran" />
                            <x-input id="tanggal_bayar" type="date" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50" wire:model="tanggal_bayar" />
                            @error('tanggal_bayar')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="status" value="Status" />
                            <x-select id="status" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50" wire:model="status">
                                <option value="">Pilih Status</option>
                                <option value="PENDING" @if($pembayaran && $pembayaran->status == 'PENDING') selected @endif>Pending</option>
                                <option value="DIBAYAR" @if($pembayaran && $pembayaran->status == 'DIBAYAR') selected @endif>Dibayar</option>
                                <option value="SELESAI" @if($pembayaran && $pembayaran->status == 'SELESAI') selected @endif>Selesai</option>
                                <option value="DITOLAK" @if($pembayaran && $pembayaran->status == 'DITOLAK') selected @endif>Ditolak</option>
                            </x-select>
                            @error('status')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Kanan: Metode & Bukti Transfer -->
                    <div class="md:col-span-1 flex flex-col items-center justify-start gap-4">
                        <div class="w-full">
                            <x-label for="metode" value="Metode Pembayaran" />
                            <x-select id="metode" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50" wire:model="metode">
                                <option value="">Pilih Metode</option>
                                <option value="transfer" @if($pembayaran && $pembayaran->metode == 'transfer') selected @endif>Transfer Bank</option>
                                <option value="cash" @if($pembayaran && $pembayaran->metode == 'cash') selected @endif>Tunai</option>
                            </x-select>
                            @error('metode')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full mt-4">
                            <x-label for="bukti_transfer" value="Bukti Transfer" />
                            @if($pembayaran && $pembayaran->bukti_transfer && $pembayaran->metode === 'transfer')
                                <div class="mb-3 w-full flex flex-col items-center">
                                    <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="w-40 h-40 object-cover rounded-lg shadow-sm border border-gray-200" />
                                    <button type="button" class="mt-2 px-4 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow text-sm" x-data="{ showInput: false }" @click="$refs.inputBukti.click()">Ubah Bukti</button>
                                    <input x-ref="inputBukti" type="file" class="hidden" wire:model="bukti_transfer" accept="image/*,.pdf" />
                                </div>
                            @endif
                            <div class="w-full" x-data="{ isTransfer: @entangle('metode').defer === 'transfer' }">
                                <div x-show="isTransfer">
                                    @if (!($pembayaran && $pembayaran->bukti_transfer && $pembayaran->metode === 'transfer'))
                                        <x-input id="bukti_transfer" type="file" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50" wire:model="bukti_transfer" accept="image/*,.pdf" />
                                    @endif
                                </div>
                                @error('bukti_transfer')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                                @if (isset($bukti_transfer) && $bukti_transfer)
                                    <div class="mt-2 flex justify-center">
                                        <img src="{{ $bukti_transfer->temporaryUrl() }}" class="w-40 h-40 object-cover rounded-lg shadow-sm border border-gray-200" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-8 gap-3">
                    <a href="{{ route('pembayaran.index') }}" class="px-8 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg shadow transition-all duration-200">Batal</a>
                    <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition-all duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>