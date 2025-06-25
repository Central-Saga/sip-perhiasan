<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Custom Request
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="save">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="kode_request" value="Kode Request" />
                                <x-input id="kode_request" type="text" class="mt-1 block w-full bg-gray-100" wire:model="kode_request" readonly />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="pelanggan_id" value="Pelanggan" />
                                <x-select id="pelanggan_id" class="mt-1 block w-full" wire:model="pelanggan_id">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="pelanggan_id" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="status" value="Status" />
                                <x-select id="status" class="mt-1 block w-full" wire:model="status">
                                    <option value="">Pilih Status</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="PROSES">Proses</option>
                                    <option value="SELESAI">Selesai</option>
                                    <option value="DITOLAK">Ditolak</option>
                                </x-select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="jenis" value="Jenis Perhiasan" />
                                <x-select id="jenis" class="mt-1 block w-full" wire:model="jenis">
                                    <option value="">Pilih Jenis</option>
                                    <option value="CINCIN">Cincin</option>
                                    <option value="KALUNG">Kalung</option>
                                    <option value="GELANG">Gelang</option>
                                    <option value="ANTING">Anting</option>
                                    <option value="LIONTIN">Liontin</option>
                                </x-select>
                                <x-input-error for="jenis" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="deskripsi" value="Deskripsi" />
                                <x-textarea id="deskripsi" class="mt-1 block w-full" wire:model="deskripsi" rows="4" />
                                <x-input-error for="deskripsi" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="referensi" value="Gambar Referensi" />
                                @if ($customRequest->referensi)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($customRequest->referensi) }}" target="_blank" class="text-blue-600 hover:underline">
                                            Lihat Gambar Sekarang
                                        </a>
                                    </div>
                                @endif
                                <input type="file" wire:model="referensi" class="mt-1 block w-full" accept="image/*"/>
                                <x-input-error for="referensi" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-button type="submit">
                                    Simpan Perubahan
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>