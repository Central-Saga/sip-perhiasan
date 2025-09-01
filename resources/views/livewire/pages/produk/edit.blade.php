<?php
use function Livewire\Volt\{ layout, title, state, mount };
use function Livewire\Volt\{ usesFileUploads };
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

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
    'status' => false,
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
    $this->status = (bool) $produk->status;
    $this->existingFoto = $produk->foto;
});

$update = function () {
    $this->validate([
        'nama_produk' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|numeric',
        'deskripsi' => 'required|string',
        'newFoto' => 'nullable|image|max:2048',
    ]);

    $produk = Produk::findOrFail($this->produkId);
    $fotoPath = $this->existingFoto;
    if ($this->newFoto) {
        $fotoPath = $this->newFoto->store('produk', 'public');
    }

    $produk->update([
        'nama_produk' => $this->nama_produk,
        'kategori' => $this->kategori,
        'harga' => $this->harga,
        'stok' => $this->stok,
        'deskripsi' => $this->deskripsi,
        'status' => (bool) $this->status,
        'foto' => $fotoPath,
    ]);

    session()->flash('success', 'Produk berhasil diperbarui!');
    return redirect()->route('produk.index');
};
?>

<div>
    <div class="max-w-7xl mx-auto py-10">
        <div class="bg-white border border-gray-200 shadow-xl rounded-2xl p-10">
            <div class="flex items-center mb-8">
                <div class="p-4 bg-white rounded-xl shadow mr-5">
                    <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Edit Produk
                        <span class="ml-2 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            ID: {{ $produkId ?? '-' }}
                        </span>
                    </h2>
                    <p class="text-base text-gray-600 mt-1">Perbarui informasi produk perhiasan Anda</p>
                </div>
            </div>
            <form wire:submit.prevent="update">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Kiri: Field -->
                    <div class="space-y-6">
                        <div>
                            <label for="nama_produk" class="block text-base font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" wire:model.defer="nama_produk" id="nama_produk" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-base border border-gray-300 rounded-lg py-3 px-4 bg-white">
                            @error('nama_produk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="kategori" class="block text-base font-medium text-gray-700 mb-1">Kategori</label>
                            <select wire:model.defer="kategori" id="kategori" class="mt-1 block w-full py-3 px-4 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base">
                                <option value="">Pilih Kategori</option>
                                <option value="Cincin">Cincin</option>
                                <option value="Kalung">Kalung</option>
                                <option value="Gelang">Gelang</option>
                                <option value="Anting">Anting</option>
                            </select>
                            @error('kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harga" class="block text-base font-medium text-gray-700 mb-1">Harga</label>
                                <div class="flex rounded-lg shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-base">Rp</span>
                                    <input type="number" wire:model.defer="harga" id="harga" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-lg text-base border border-gray-300 py-3 px-4 bg-white">
                                </div>
                                @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="stok" class="block text-base font-medium text-gray-700 mb-1">Stok</label>
                                <input type="number" wire:model.defer="stok" id="stok" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-base border border-gray-300 rounded-lg py-3 px-4 bg-white">
                                @error('stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-base font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea wire:model.defer="deskripsi" id="deskripsi" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-base border border-gray-300 rounded-lg py-3 px-4 bg-white"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model.defer="status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-base text-gray-600">Aktif</span>
                            </label>
                        </div>
                    </div>
                    <!-- Kanan: Preview Foto & Upload -->
                    <div class="flex flex-col items-center justify-center h-full space-y-6">
                        <div class="w-56 h-56 flex items-center justify-center bg-white rounded-xl border-2 border-dashed border-indigo-200 shadow-sm overflow-hidden">
                            @if($existingFoto && !$newFoto)
                                <img src="{{ Storage::url($existingFoto) }}" class="object-cover w-full h-full">
                            @elseif($newFoto && is_object($newFoto) && method_exists($newFoto, 'temporaryUrl'))
                                <img src="{{ $newFoto->temporaryUrl() }}" class="object-cover w-full h-full">
                            @else
                                <div class="flex flex-col items-center justify-center text-indigo-300">
                                    <svg class="h-12 w-12 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
                                    <span class="text-sm">Belum ada foto</span>
                                </div>
                            @endif
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <input type="file" wire:model="newFoto" id="foto" class="sr-only" accept="image/*">
                            <label for="foto" class="cursor-pointer bg-indigo-600 py-2 px-6 rounded-lg text-white font-semibold shadow hover:bg-indigo-700 transition-all">
                                Ganti Foto
                            </label>
                            @error('newFoto') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-10 flex justify-end space-x-3">
                    <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow">Batal</a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg text-white hover:bg-indigo-700 font-semibold shadow">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Perbarui
                    </button>
                </div>
                @if (session()->has('success'))
                    <div class="mt-6 p-4 bg-green-100 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="mt-6 p-4 bg-red-100 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

