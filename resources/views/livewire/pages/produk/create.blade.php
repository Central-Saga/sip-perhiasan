<?php

namespace App\Livewire\Pages\Produk;

use App\Models\Produk;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $nama_produk;
    public $kategori;
    public $harga;
    public $stok;
    public $deskripsi;
    public $foto;
    public $status = false;

    public function save()
    {
        $validated = $this->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        if ($this->foto) {
            $validated['foto'] = $this->foto->store('produk', 'public');
        }

        Produk::create($validated);

        session()->flash('message', 'Produk berhasil ditambahkan!');
        return redirect()->route('produk.index');
    }

    public function render()
    {
        return view('livewire.pages.produk.create');
    }
}
?>
<div class="py-10 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center space-x-4 mb-8">
            <div class="p-4 bg-white rounded-xl shadow">
                <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Tambah Produk Baru</h2>
                <p class="text-base text-gray-600 mt-1 flex items-center">
                    <svg class="h-5 w-5 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"></svg> Tambahkan produk perhiasan baru ke dalam sistem.
                </p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 shadow-xl rounded-2xl p-10">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Kiri: Field utama + deskripsi + status -->
                    <div class="space-y-6 flex flex-col justify-between h-full">
                        <div>
                            <label for="nama_produk" class="mb-1 font-semibold text-gray-700 text-base">Nama Produk</label>
                            <input type="text" wire:model="nama_produk" id="nama_produk" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Nama produk">
                            @error('nama_produk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="kategori" class="mb-1 font-semibold text-gray-700 text-base">Kategori</label>
                            <select wire:model="kategori" id="kategori" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white appearance-none">
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
                                <label for="harga" class="mb-1 font-semibold text-gray-700 text-base">Harga</label>
                                <div class="flex rounded-lg shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-base">Rp</span>
                                    <input type="number" wire:model="harga" id="harga" class="w-full py-3 px-4 text-base border border-gray-300 rounded-none rounded-r-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Harga produk">
                                </div>
                                @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="stok" class="mb-1 font-semibold text-gray-700 text-base">Stok</label>
                                <input type="number" wire:model="stok" id="stok" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Stok produk">
                                @error('stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="deskripsi" class="mb-1 font-semibold text-gray-700 text-base">Deskripsi</label>
                            <textarea wire:model="deskripsi" id="deskripsi" rows="5" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Deskripsi produk..."></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-base text-gray-600">Aktif</span>
                            </label>
                        </div>
                    </div>
                    <!-- Kanan: Preview Foto & Upload -->
                    <div class="flex flex-col items-center justify-center h-full space-y-6">
                        <div class="w-56 h-56 flex items-center justify-center bg-white rounded-xl border-2 border-dashed border-indigo-200 shadow-sm overflow-hidden">
                            @isset($this->foto)
                                @if ($this->foto instanceof \Livewire\TemporaryUploadedFile)
                                    <img src="{{ $this->foto->temporaryUrl() }}" class="object-cover w-full h-full">
                                @else
                                    <div class="flex flex-col items-center justify-center text-indigo-300">
                                        <svg class="h-12 w-12 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
                                        <span class="text-sm">Belum ada foto</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col items-center justify-center text-indigo-300">
                                    <svg class="h-12 w-12 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
                                    <span class="text-sm">Belum ada foto</span>
                                </div>
                            @endisset
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <input type="file" wire:model="foto" id="foto" class="sr-only">
                            <label for="foto" class="cursor-pointer bg-indigo-600 py-2 px-6 rounded-lg text-white font-semibold shadow hover:bg-indigo-700 transition-all">
                                Pilih Foto
                            </label>
                            @error('foto') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-10 gap-3">
                    <a href="{{ route('produk.index') }}" class="px-6 py-3 text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium shadow">Batal</a>
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
