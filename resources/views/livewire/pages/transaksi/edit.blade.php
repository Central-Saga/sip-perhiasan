<?php
use Livewire\Volt\Component;
new class extends Component {
    //
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="update">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 mb-4">
                                    <div class="text-sm text-gray-700">
                                        <p>Pelanggan: <span class="font-medium">{{ $transaksi->user->name }}</span></p>
                                        <p>Produk: <span class="font-medium">{{ $transaksi->produk->nama_produk }}</span></p>
                                        <p>Tanggal Transaksi: <span class="font-medium">{{ $transaksi->tanggal_transaksi->format('d M Y H:i') }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <x-label for="jumlah" value="Jumlah" />
                                <x-input id="jumlah" type="number" class="mt-1 block w-full" wire:model="jumlah" min="1" />
                                <x-input-error for="jumlah" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-label for="status" value="Status" />
                                <x-select id="status" class="mt-1 block w-full" wire:model="status">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </x-select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            @if($totalHarga)
                                <div class="col-span-6">
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6">
                                        <div class="text-lg font-medium text-gray-900">
                                            Total Harga: Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Pembayaran Section -->
                            <div class="col-span-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="pembayaran.metode" value="Metode Pembayaran" />
                                        <x-select id="pembayaran.metode" class="mt-1 block w-full" wire:model="pembayaran.metode">
                                            <option value="transfer_bank">Transfer Bank</option>
                                            <option value="cash">Cash</option>
                                            <option value="e-wallet">E-Wallet</option>
                                        </x-select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="pembayaran.status" value="Status Pembayaran" />
                                        <x-select id="pembayaran.status" class="mt-1 block w-full" wire:model="pembayaran.status">
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="failed">Failed</option>
                                        </x-select>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengiriman Section -->
                            <div class="col-span-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h3>
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="pengiriman.status" value="Status Pengiriman" />
                                        <x-select id="pengiriman.status" class="mt-1 block w-full" wire:model="pengiriman.status">
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                        </x-select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="pengiriman.deskripsi" value="Deskripsi Pengiriman" />
                                        <x-textarea id="pengiriman.deskripsi" class="mt-1 block w-full" wire:model="pengiriman.deskripsi" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-button.link href="{{ route('transaksi.index') }}" class="mr-3">
                                {{ __('Batal') }}
                            </x-button.link>

                            <x-button>
                                {{ __('Perbarui') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
