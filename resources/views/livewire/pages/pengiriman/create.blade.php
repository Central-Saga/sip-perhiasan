<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{ layout, title };
layout('components.layouts.admin');
title('Pengiriman - Tambah');

new class extends Component {
    //
}; ?>

@php
    use App\Models\Transaksi;
    $transaksis = Transaksi::all();
@endphp
<div>
    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Tambah Pengiriman Baru</h2>
                <p class="text-base text-gray-600 mt-1">Masukkan detail pengiriman dengan lengkap.</p>
            </div>
            <div class="bg-white border border-gray-200 shadow-xl rounded-2xl p-10">
                <form method="POST" action="{{ route('pengiriman.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label for="transaksi_id" class="block text-base font-medium text-gray-700 mb-1">Transaksi</label>
                                <select id="transaksi_id" name="transaksi_id" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Pilih Transaksi</option>
                                    @foreach($transaksis as $transaksi)
                                        <option value="{{ $transaksi->id }}">{{ $transaksi->kode_transaksi }}</option>
                                    @endforeach
                                </select>
                                @error('transaksi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-base font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Pilih Status</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="DIKIRIM">Dikirim</option>
                                    <option value="SELESAI">Selesai</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="kurir" class="block text-base font-medium text-gray-700 mb-1">Kurir</label>
                                <input type="text" id="kurir" name="kurir" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Nama kurir" value="{{ old('kurir') }}">
                                @error('kurir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="no_resi" class="block text-base font-medium text-gray-700 mb-1">Nomor Resi</label>
                                <input type="text" id="no_resi" name="no_resi" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Nomor resi pengiriman" value="{{ old('no_resi') }}">
                                @error('no_resi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="alamat" class="block text-base font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="4" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Alamat pengiriman">{{ old('alamat') }}</textarea>
                                @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <a href="{{ route('pengiriman.index') }}" class="px-6 py-3 text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium shadow">Batal</a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
