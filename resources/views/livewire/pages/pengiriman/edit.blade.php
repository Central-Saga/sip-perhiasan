<?php
use function Livewire\\Volt\\{ layout, title };
layout('components.layouts.admin');
title('Pengiriman - Edit');

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

@php
    use App\Models\Transaksi;
    use App\Models\Pengiriman;
    $id = request()->route('pengiriman');
    $pengiriman = Pengiriman::findOrFail($id);
    $transaksis = Transaksi::all();
    $statusList = ['pending', 'processing', 'shipped', 'delivered'];
@endphp
<div>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="bg-white shadow-xl rounded-2xl p-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Pengiriman</h2>
            <form method="POST" action="{{ route('pengiriman.edit', $pengiriman->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label for="transaksi_id" class="block text-base font-medium text-gray-700 mb-1">Transaksi</label>
                            <select id="transaksi_id" name="transaksi_id" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Pilih Transaksi</option>
                                @foreach($transaksis as $transaksi)
                                    <option value="{{ $transaksi->id }}" {{ old('transaksi_id', $pengiriman->transaksi_id) == $transaksi->id ? 'selected' : '' }}>{{ $transaksi->kode_transaksi }}</option>
                                @endforeach
                            </select>
                            @error('transaksi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-base font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Pilih Status</option>
                                @foreach($statusList as $status)
                                    <option value="{{ $status }}" {{ old('status', $pengiriman->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label for="deskripsi" class="block text-base font-medium text-gray-700 mb-1">Deskripsi</label>
                            <input id="deskripsi" type="text" name="deskripsi" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" value="{{ old('deskripsi', $pengiriman->deskripsi) }}">
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tanggal_pengiriman" class="block text-base font-medium text-gray-700 mb-1">Tanggal Pengiriman</label>
                            <input id="tanggal_pengiriman" type="date" name="tanggal_pengiriman" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" value="{{ old('tanggal_pengiriman', optional($pengiriman->tanggal_pengiriman)->format('Y-m-d')) }}">
                            @error('tanggal_pengiriman') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-10 gap-3">
                    <a href="{{ route('pengiriman.index') }}" class="px-6 py-3 text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium shadow">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
