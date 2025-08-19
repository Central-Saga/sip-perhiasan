<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="py-12 bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="user-plus" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Pelanggan Baru</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Isi data pelanggan dengan benar untuk menambah ke daftar pelanggan.
                    </p>
                </div>
            </div>
            <!-- Form Card -->
            <div class="bg-white/90 backdrop-blur-xl border border-gray-200 shadow-xl rounded-2xl p-8">
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <x-label for="name" value="Nama" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="user" class="h-5 w-5" />
                            </span>
                            <x-input id="name" type="text" class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200" wire:model="name" placeholder="Nama pelanggan" />
                            @error('name')
                                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="email" value="Email" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="envelope" class="h-5 w-5" />
                            </span>
                            <x-input id="email" type="email" class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200" wire:model="email" placeholder="Email pelanggan" />
                            @error('email')
                                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="no_telepon" value="Nomor Telepon" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="phone" class="h-5 w-5" />
                            </span>
                            <x-input id="no_telepon" type="text" class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200" wire:model="no_telepon" placeholder="08xxxxxxxxxx" />
                            @error('no_telepon')
                                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="alamat" value="Alamat" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="map-pin" class="h-5 w-5" />
                            </span>
                            <x-textarea id="alamat" class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200" wire:model="alamat" placeholder="Alamat lengkap pelanggan..." />
                            @error('alamat')
                                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="flex items-center">
                                <x-checkbox wire:model="status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Aktif') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-button.link href="{{ route('pelanggan.index') }}" class="px-5 py-2 text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium transition-all duration-150">
                            {{ __('Batal') }}
                        </x-button.link>
                        <x-button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md transition-all duration-150">
                            {{ __('Simpan') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
