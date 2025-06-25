<x-layouts.app>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Pembayaran Baru
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="save">
                        <div class="grid grid-cols-6 gap-6">
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
                                    <option value="DIBAYAR">Dibayar</option>
                                    <option value="SELESAI">Selesai</option>
                                    <option value="DITOLAK">Ditolak</option>
                                </x-select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="metode" value="Metode Pembayaran" />
                                <x-select id="metode" class="mt-1 block w-full" wire:model="metode">
                                    <option value="">Pilih Metode</option>
                                    <option value="TRANSFER">Transfer Bank</option>
                                    <option value="CASH">Tunai</option>
                                </x-select>
                                <x-input-error for="metode" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="total" value="Total Pembayaran" />
                                <x-input id="total" type="number" class="mt-1 block w-full" wire:model="total" />
                                <x-input-error for="total" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="bukti" value="Bukti Pembayaran" />
                                <input type="file" wire:model="bukti" class="mt-1 block w-full" accept="image/*"/>
                                <x-input-error for="bukti" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-button type="submit">
                                    Simpan
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>