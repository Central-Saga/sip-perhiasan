<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengiriman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="save">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="kode_pengiriman" value="Kode Pengiriman" />
                                <x-input id="kode_pengiriman" type="text" class="mt-1 block w-full bg-gray-100" wire:model="kode_pengiriman" readonly />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="transaksi_id" value="Transaksi" />
                                <x-select id="transaksi_id" class="mt-1 block w-full" wire:model="transaksi_id">
                                    <option value="">Pilih Transaksi</option>
                                    @foreach($transaksis as $transaksi)
                                        <option value="{{ $transaksi->id }}">{{ $transaksi->kode_transaksi }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="transaksi_id" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="status" value="Status" />
                                <x-select id="status" class="mt-1 block w-full" wire:model="status">
                                    <option value="">Pilih Status</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="DIKIRIM">Dikirim</option>
                                    <option value="SELESAI">Selesai</option>
                                </x-select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="kurir" value="Kurir" />
                                <x-input id="kurir" type="text" class="mt-1 block w-full" wire:model="kurir" />
                                <x-input-error for="kurir" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="no_resi" value="Nomor Resi" />
                                <x-input id="no_resi" type="text" class="mt-1 block w-full" wire:model="no_resi" />
                                <x-input-error for="no_resi" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="alamat" value="Alamat" />
                                <x-textarea id="alamat" class="mt-1 block w-full" wire:model="alamat" />
                                <x-input-error for="alamat" class="mt-2" />
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