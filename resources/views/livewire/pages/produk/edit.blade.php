<?php

use function Livewire\Volt\{ layout, title, state, mount, computed };
use function Livewire\Volt\{ usesFileUploads };
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

layout('components.layouts.admin');
title('Produk - Edit');

usesFileUploads();

state([
    'produkId' => null,
    'nama_produk' => '',
    'kategori' => '',
    'harga' => '',
    'stok' => '',
    'deskripsi' => '',
    'status' => 'Aktif',
    'existingFoto' => null,
    'newFoto' => null,
]);

mount(function (Produk $produk) {
    $this->produkId = $produk->id;
    $this->nama_produk = $produk->nama_produk;
    $this->kategori = $produk->kategori;
    $this->harga = $produk->harga;
    $this->stok = $produk->stok;
    $this->deskripsi = $produk->deskripsi;
    $this->status = $produk->status;
    $this->existingFoto = $produk->foto;
});

$save = function () {
    try {
        $produk = Produk::findOrFail($this->produkId);

        // Log awal proses update
        Log::channel('produk_management')->info('Produk Update Started', [
            'produk_id' => $produk->id,
            'current_name' => $produk->nama_produk,
            'current_category' => $produk->kategori,
            'new_name' => $this->nama_produk,
            'new_category' => $this->kategori,
            'new_price' => $this->harga,
            'new_stock' => $this->stok,
            'new_status' => $this->status,
            'has_new_photo' => $this->newFoto ? true : false,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $validated = $this->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'newFoto' => 'nullable|image|max:2048',
        ]);

        Log::channel('produk_management')->info('Produk Validation Passed', [
            'produk_id' => $produk->id,
            'validated_data' => $validated
        ]);

        $fotoPath = $this->existingFoto;
        if ($this->newFoto) {
            // Hapus foto lama jika ada
            if ($this->existingFoto && Storage::disk('public')->exists($this->existingFoto)) {
                Storage::disk('public')->delete($this->existingFoto);
            }
            $fotoPath = $this->newFoto->store('produk', 'public');

            Log::channel('produk_management')->info('New Photo Uploaded', [
                'produk_id' => $produk->id,
                'old_photo' => $this->existingFoto,
                'new_photo' => $fotoPath
            ]);
        }

        // Update produk data
        $produk->update([
            'nama_produk' => $validated['nama_produk'],
            'kategori' => $validated['kategori'],
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'deskripsi' => $validated['deskripsi'],
            'status' => $validated['status'],
            'foto' => $fotoPath,
        ]);

        Log::channel('produk_management')->info('Produk Update Completed Successfully', [
            'produk_id' => $produk->id,
            'updated_name' => $validated['nama_produk'],
            'updated_at' => now()
        ]);

        session()->flash('success', 'Produk berhasil diperbarui!');
        return redirect()->route('produk.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('produk_management')->error('Produk Update Validation Failed', [
            'produk_id' => $this->produkId,
            'errors' => $e->errors(),
            'input_data' => [
                'nama_produk' => $this->nama_produk,
                'kategori' => $this->kategori,
                'harga' => $this->harga,
                'stok' => $this->stok,
                'deskripsi' => $this->deskripsi,
                'status' => $this->status
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('produk_management')->error('Produk Update Failed', [
            'produk_id' => $this->produkId,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'nama_produk' => $this->nama_produk,
                'kategori' => $this->kategori,
                'harga' => $this->harga,
                'stok' => $this->stok,
                'deskripsi' => $this->deskripsi,
                'status' => $this->status
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage());
        return;
    }
};

// Get kategori options for the form
$kategoriOptions = computed(function () {
    return [
        ['id' => 'Cincin', 'name' => 'Cincin'],
        ['id' => 'Kalung', 'name' => 'Kalung'],
        ['id' => 'Gelang', 'name' => 'Gelang'],
        ['id' => 'Anting', 'name' => 'Anting'],
    ];
});

// Get status options for the form
$statusOptions = computed(function () {
    return [
        ['id' => 'Aktif', 'name' => 'Aktif', 'hint' => 'Produk dapat dijual.'],
        ['id' => 'Tidak Aktif', 'name' => 'Tidak Aktif', 'hint' => 'Produk tidak dapat dijual.'],
    ];
});
?>

<div>
    <div class="py-12 bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="pencil-square" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Produk</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Perbarui data produk perhiasan yang ada.
                    </p>
                </div>
            </div>

            <!-- Form Card dengan Mary UI -->
            <x-mary-card class="bg-white/90 backdrop-blur-xl shadow-xl p-8">
                <form wire:submit="save" enctype="multipart/form-data">
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
                            <x-mary-select label="Kategori" wire:model="kategori" :options="$this->kategoriOptions"
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
                            <x-mary-radio label="Status Produk" wire:model="status" :options="$this->statusOptions"
                                class="radio-primary" />
                            @error('status')
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror
                        </div>

                        <!-- Foto Upload -->
                        <x-mary-file label="Foto Produk" wire:model="newFoto" accept="image/*"
                            class="file-input-bordered" />
                        @error('newFoto')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Preview Foto -->
                        @if($existingFoto || $newFoto)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Preview Foto</span>
                            </label>
                            <div class="flex justify-center">
                                <div
                                    class="w-64 h-64 bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden">
                                    @if($newFoto && is_object($newFoto) && method_exists($newFoto, 'temporaryUrl'))
                                    <img src="{{ $newFoto->temporaryUrl() }}"
                                        class="object-cover w-full h-full rounded-xl" alt="Preview foto baru">
                                    @elseif($existingFoto)
                                    <img src="{{ Storage::url($existingFoto) }}"
                                        class="object-cover w-full h-full rounded-xl" alt="Foto produk saat ini">
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                @if($newFoto)
                                Preview foto baru yang akan diupload
                                @else
                                Foto produk saat ini
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                    <!-- Tombol -->
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" link="{{ route('produk.index') }}" class="btn-outline" />
                        <x-mary-button label="Perbarui" type="submit" class="btn-primary" spinner="save" />
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