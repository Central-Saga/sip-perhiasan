<?php

use function Livewire\Volt\{ layout, title, state, mount };
use App\Models\Pelanggan;
use Illuminate\Validation\Rule;

layout('components.layouts.admin');
title('Pelanggan - Edit');

state([
    'pelangganId' => null,
    'name' => '',
    'email' => '',
    'no_telepon' => '',
    'alamat' => '',
    'status' => 'Aktif',
]);

mount(function (Pelanggan $pelanggan) {
    $this->pelangganId = $pelanggan->id;
    $this->name = $pelanggan->user->name ?? '';
    $this->email = $pelanggan->user->email ?? '';
    $this->no_telepon = $pelanggan->no_telepon ?? '';
    $this->alamat = $pelanggan->alamat ?? '';
    $this->status = $pelanggan->status ?? 'Aktif';
});

$update = function () {
    $pelanggan = Pelanggan::with('user')->findOrFail($this->pelangganId);
    $userId = $pelanggan->user_id;

    $this->validate([
        'name' => ['required', 'string', 'min:3', 'max:255'],
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
        'no_telepon' => ['required', 'string', 'min:8', 'max:20'],
        'alamat' => ['required', 'string', 'min:10'],
        'status' => ['required', 'in:Aktif,Tidak Aktif'],
    ]);

    if ($pelanggan->user) {
        $pelanggan->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
    }

    $pelanggan->update([
        'no_telepon' => $this->no_telepon,
        'alamat' => $this->alamat,
        'status' => $this->status,
    ]);

    session()->flash('success', 'Data pelanggan berhasil diperbarui.');
    return redirect()->route('pelanggan.index');
};
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
                    <h2 class="text-2xl font-bold text-gray-900">Edit Pelanggan</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Perbarui data pelanggan yang ada.
                    </p>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <flux:icon name="check-circle" class="h-5 w-5 mr-2" />
                {{ session('success') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <flux:icon name="exclamation-triangle" class="h-5 w-5 mr-2" />
                {{ session('error') }}
            </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white/90 backdrop-blur-xl border border-gray-200 shadow-xl rounded-2xl p-8">
                <form wire:submit="update">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="user" class="h-5 w-5" />
                            </span>
                            <input id="name" type="text"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200"
                                wire:model="name" placeholder="Nama lengkap pelanggan" />
                            <p class="text-xs text-gray-500 mt-1">Nama harus minimal 3 karakter</p>
                            @error('name')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="envelope" class="h-5 w-5" />
                            </span>
                            <input id="email" type="email"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200"
                                wire:model="email" placeholder="contoh@email.com" />
                            <p class="text-xs text-gray-500 mt-1">Email harus unik dan valid</p>
                            @error('email')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="phone" class="h-5 w-5" />
                            </span>
                            <input id="no_telepon" type="tel"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200"
                                wire:model="no_telepon" placeholder="08xxxxxxxxxx" />
                            <p class="text-xs text-gray-500 mt-1">Nomor telepon minimal 8 digit</p>
                            @error('no_telepon')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="map-pin" class="h-5 w-5" />
                            </span>
                            <textarea id="alamat"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200 resize-none"
                                wire:model="alamat" placeholder="Alamat lengkap pelanggan..." rows="3"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Alamat minimal 10 karakter</p>
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
                                    <label
                                        class="flex items-center p-3 hover:bg-gray-100 rounded-lg transition-colors duration-150 cursor-pointer">
                                        <input type="radio" wire:model="status" value="Aktif"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                                        <span class="ml-3 text-sm text-gray-700">Aktif</span>
                                    </label>
                                    <label
                                        class="flex items-center p-3 hover:bg-gray-100 rounded-lg transition-colors duration-150 cursor-pointer">
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
                            {{ __('Perbarui') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
