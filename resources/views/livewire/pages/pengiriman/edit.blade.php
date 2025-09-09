<?php
use function Livewire\Volt\{ layout, title, state, mount };
use App\Models\Pengiriman;
use App\Models\Transaksi;

layout('components.layouts.admin');
title('Pengiriman - Edit');

// Form state
state([
    'pengirimanId' => null,
    'transaksi_id' => '',
    'status' => 'PENDING',
    'deskripsi' => '',
    'tanggal_pengiriman' => '',
]);

mount(function (Pengiriman $pengiriman) {
    $this->pengirimanId = $pengiriman->id;
    $this->transaksi_id = $pengiriman->transaksi_id;
    $this->status = $pengiriman->status;
    $this->deskripsi = $pengiriman->deskripsi;
    $this->tanggal_pengiriman = $pengiriman->tanggal_pengiriman ? $pengiriman->tanggal_pengiriman->format('Y-m-d') : '';
});

$getStatusOptions = function () {
    return [
        ['id' => 'PENDING', 'name' => 'Pending', 'hint' => 'Menunggu pengiriman.'],
        ['id' => 'DIKIRIM', 'name' => 'Dikirim', 'hint' => 'Sedang dalam perjalanan.'],
        ['id' => 'SELESAI', 'name' => 'Selesai', 'hint' => 'Sudah sampai tujuan.'],
    ];
};

$getTransaksiOptions = function () {
    return Transaksi::all()->map(function ($transaksi) {
        return [
            'id' => $transaksi->id,
            'name' => $transaksi->kode_transaksi,
            'hint' => 'Transaksi #' . $transaksi->id
        ];
    })->toArray();
};

$update = function () {
    try {
        $this->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'status' => 'required|in:PENDING,DIKIRIM,SELESAI',
            'deskripsi' => 'required|string|max:500',
            'tanggal_pengiriman' => 'required|date',
        ]);

        $pengiriman = Pengiriman::findOrFail($this->pengirimanId);
        $pengiriman->update([
            'transaksi_id' => $this->transaksi_id,
            'status' => $this->status,
            'deskripsi' => $this->deskripsi,
            'tanggal_pengiriman' => $this->tanggal_pengiriman,
        ]);

        session()->flash('success', 'Pengiriman berhasil diperbarui!');
        return redirect()->route('pengiriman.index');
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal memperbarui pengiriman: ' . $e->getMessage());
        return back();
    }
};
?>

<div>
    <div class="py-12 bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="paper-airplane" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Pengiriman</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Perbarui data pengiriman untuk melacak pesanan perhiasan.
                    </p>
                </div>
            </div>

            <!-- Form Card dengan Mary UI -->
            <x-mary-card class="bg-white/90 backdrop-blur-xl shadow-xl p-8">
                <form wire:submit.prevent="update">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Transaksi -->
                        <div class="form-control">
                            <x-mary-select label="Transaksi" wire:model="transaksi_id"
                                :options="$this->getTransaksiOptions()" icon="o-shopping-cart"
                                placeholder="Pilih Transaksi" />
                        </div>
                        @error('transaksi_id')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Status -->
                        <div class="form-control">
                            <x-mary-radio label="Status Pengiriman" wire:model="status"
                                :options="$this->getStatusOptions()" class="radio-primary" />
                        </div>
                        @error('status')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Deskripsi -->
                        <x-mary-textarea label="Deskripsi" wire:model="deskripsi" placeholder="Deskripsi pengiriman..."
                            icon="o-document-text" rows="4" class="textarea-bordered" />
                        @error('deskripsi')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Tanggal Pengiriman -->
                        <x-mary-input label="Tanggal Pengiriman" wire:model="tanggal_pengiriman" type="date"
                            icon="o-calendar" class="input-bordered" />
                        @error('tanggal_pengiriman')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" link="{{ route('pengiriman.index') }}" class="btn-outline" />
                        <x-mary-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="update" />
                    </div>
                </form>

                <!-- Flash Messages -->
                @if (session()->has('success'))
                <x-mary-alert icon="o-check-circle" class="alert-success mt-6">
                    {{ session('success') }}
                </x-mary-alert>
                @endif

                @if (session()->has('error'))
                <x-mary-alert icon="o-exclamation-triangle" class="alert-error mt-6">
                    {{ session('error') }}
                </x-mary-alert>
                @endif
            </x-mary-card>
        </div>
    </div>
</div>