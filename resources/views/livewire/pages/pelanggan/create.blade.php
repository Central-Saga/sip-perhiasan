<?php

use function Livewire\Volt\{ layout, title, state };
use App\Models\Pelanggan;
use App\Models\User;

layout('components.layouts.admin');
title('Pelanggan - Tambah');

state([
    'name' => '',
    'email' => '',
    'no_telepon' => '',
    'alamat' => '',
    'status' => 'Aktif',
]);

$save = function () {
    $validated = $this->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'no_telepon' => 'required|string|min:8',
        'alamat' => 'required|string|min:5',
        'status' => 'required|in:Aktif,Tidak Aktif',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => 'password',
    ]);

    Pelanggan::create([
        'user_id' => $user->id,
        'no_telepon' => $validated['no_telepon'],
        'alamat' => $validated['alamat'],
        'status' => $this->status,
    ]);

    session()->flash('success', 'Pelanggan berhasil ditambahkan!');
    return redirect()->route('pelanggan.index');
};
?>

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
                            <x-input id="name" type="text"
                                class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200"
                                wire:model="name" placeholder="Nama pelanggan" />
                            @error('name')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="email" value="Email" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="envelope" class="h-5 w-5" />
                            </span>
                            <x-input id="email" type="email"
                                class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200"
                                wire:model="email" placeholder="Email pelanggan" />
                            @error('email')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="no_telepon" value="Nomor Telepon" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="phone" class="h-5 w-5" />
                            </span>
                            <x-input id="no_telepon" type="text"
                                class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200"
                                wire:model="no_telepon" placeholder="08xxxxxxxxxx" />
                            @error('no_telepon')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative">
                            <x-label for="alamat" value="Alamat" class="mb-1 font-semibold text-gray-700" />
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="map-pin" class="h-5 w-5" />
                            </span>
                            <x-textarea id="alamat"
                                class="mt-1 block w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 bg-gray-50 rounded-lg transition-all duration-200"
                                wire:model="alamat" placeholder="Alamat lengkap pelanggan..." />
                            @error('alamat')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Status Section -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Status Pelanggan
                                <span class="text-xs text-gray-500 font-normal ml-1">(Pilih status pelanggan)</span>
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center p-3 hover:bg-gray-100 rounded-lg transition-colors duration-150 cursor-pointer">
                                        <input type="radio" wire:model="status" value="Aktif"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                                        <span class="ml-3 text-sm text-gray-700">Aktif</span>
                                    </label>
                                    <label class="flex items-center p-3 hover:bg-gray-100 rounded-lg transition-colors duration-150 cursor-pointer">
                                        <input type="radio" wire:model="status" value="Tidak Aktif"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                                        <span class="ml-3 text-sm text-gray-700">Tidak Aktif</span>
                                    </label>
                                </div>
                            </div>
                            @error('status')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-button.link href="{{ route('pelanggan.index') }}"
                            class="px-5 py-2 text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium transition-all duration-150">
                            {{ __('Batal') }}
                        </x-button.link>
                        <x-button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md transition-all duration-150">
                            {{ __('Simpan') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>