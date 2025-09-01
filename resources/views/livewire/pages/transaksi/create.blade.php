<?php
use Livewire\Volt\Component;
use function Livewire\Volt\{ layout, title };
layout('components.layouts.admin');
title('Transaksi - Tambah');
new class extends Component {
    //
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="save">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="user_id" value="Pelanggan" />
                                <x-select id="user_id" class="mt-1 block w-full" wire:model="user_id">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="user_id" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="produk_id" value="Produk" />
                                <x-select id="produk_id" class="mt-1 block w-full" wire:model="produk_id">
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $produk)
                                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }} - Rp {{ number_format($produk->harga, 0, ',', '.') }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="produk_id" class="mt-2" />
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
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-button.link href="{{ route('transaksi.index') }}" class="mr-3">
                                {{ __('Batal') }}
                            </x-button.link>

                            <x-button>
                                {{ __('Simpan') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
