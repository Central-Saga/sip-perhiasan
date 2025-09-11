<?php
use function Livewire\Volt\{ layout, title, state };
use function Livewire\Volt\{ usesFileUploads };
layout('components.layouts.admin');
title('Produk - Tambah');
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

// Enable file uploads for Livewire
usesFileUploads();

// Form state
state([
    'nama_produk' => '',
    'kategori' => '',
    'harga' => '',
    'stok' => '',
    'deskripsi' => '',
    'status' => 'Aktif',
    'foto' => null,
]);

$getStatusOptions = function () {
    return [
        ['id' => 'Aktif', 'name' => 'Aktif', 'hint' => 'Produk dapat dijual.'],
        ['id' => 'Tidak Aktif', 'name' => 'Tidak Aktif', 'hint' => 'Produk tidak dapat dijual.'],
    ];
};

$getKategoriOptions = function () {
    return [
        ['id' => 'Cincin', 'name' => 'Cincin'],
        ['id' => 'Kalung', 'name' => 'Kalung'],
        ['id' => 'Gelang', 'name' => 'Gelang'],
        ['id' => 'Anting', 'name' => 'Anting'],
    ];
};

$save = function () {
    $this->validate([
        'nama_produk' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|numeric',
        'deskripsi' => 'required|string',
        'status' => 'required|in:Aktif,Tidak Aktif',
        'foto' => 'nullable|image|max:2048',
    ]);

    $path = null;
    if ($this->foto) {
        $path = $this->foto->store('produk', 'public');
    }

    Produk::create([
        'nama_produk' => $this->nama_produk,
        'kategori' => $this->kategori,
        'harga' => $this->harga,
        'stok' => $this->stok,
        'deskripsi' => $this->deskripsi,
        'status' => $this->status,
        'foto' => $path,
    ]);

    session()->flash('success', 'Produk berhasil ditambahkan!');
    return redirect()->route('produk.index');
};
?>

<div>
    <div class="py-12 bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="sparkles" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Isi data produk dengan benar untuk menambah ke katalog perhiasan.
                    </p>
                </div>
            </div>

            <!-- Form Card dengan Mary UI -->
            <x-mary-card class="bg-white/90 backdrop-blur-xl shadow-xl p-8">
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nama Produk -->
                        <x-mary-input label="Nama Produk" wire:model="nama_produk" placeholder="Nama produk perhiasan"
                            icon="o-sparkles" class="input-bordered" />
                        @error('nama_produk')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Kategori -->
                        <div class="form-control">
                            <x-mary-select label="Kategori" wire:model="kategori" :options="$this->getKategoriOptions()"
                                icon="o-tag" placeholder="Pilih Kategori" />
                        </div>
                        @error('kategori')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Harga dan Stok -->
                        <div class="grid grid-cols-2 gap-4">
                            <x-mary-input label="Harga" wire:model="harga" placeholder="Harga produk" icon="o-banknotes"
                                type="number" class="input-bordered" />
                            @error('harga')
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror

                            <x-mary-input label="Stok" wire:model="stok" placeholder="Jumlah stok" icon="o-cube"
                                type="number" class="input-bordered" />
                            @error('stok')
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <x-mary-textarea label="Deskripsi" wire:model="deskripsi"
                            placeholder="Deskripsi lengkap produk..." icon="o-document-text" rows="4"
                            class="textarea-bordered" />
                        @error('deskripsi')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Status -->
                        <div class="form-control">
                            <x-mary-radio label="Status Produk" wire:model="status" :options="$this->getStatusOptions()"
                                class="radio-primary" />
                            @error('status')
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror
                        </div>

                        <!-- Foto Upload -->
                        <x-mary-file label="Foto Produk" wire:model="foto" accept="image/*"
                            class="file-input-bordered" />
                        @error('foto')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Preview Foto -->
                        <div class="mt-2">
                            <div class="text-sm text-gray-600 mb-2">Preview:</div>
                            <div class="relative w-48 h-48 rounded-lg overflow-hidden border bg-gray-50 flex items-center justify-center">
                                <div wire:loading wire:target="foto" class="absolute inset-0 grid place-content-center bg-white/60 text-gray-700 text-sm">
                                    Mengunggah...
                                </div>
                                @if ($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto Produk" class="object-cover w-full h-full">
                                @else
                                    <span class="text-gray-400 text-sm">Belum ada gambar</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" link="{{ route('produk.index') }}" class="btn-outline" />
                        <x-mary-button label="Simpan" type="submit" class="btn-primary" spinner="save" />
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
