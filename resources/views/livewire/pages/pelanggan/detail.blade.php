<?php

use function Livewire\Volt\{ layout, title, state, mount };
use App\Models\Pelanggan;

title('Detail Pelanggan');
layout('components.layouts.admin');

state([
    'pelanggan' => null,
]);

mount(function (Pelanggan $pelanggan) {
    $this->pelanggan = $pelanggan->load(['user']);
});
?>

@php
$pelanggan = $this->pelanggan;
$user = $pelanggan?->user;
@endphp

<div class="py-10">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Detail Pelanggan</h1>
                <p class="text-sm text-gray-500">Informasi lengkap untuk pelanggan ID {{ $pelanggan->id }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pelanggan.edit', $pelanggan) }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition"
                    wire:navigate>
                    <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                    Edit
                </a>
                <a href="{{ route('pelanggan.index') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    wire:navigate>
                    <flux:icon name="arrow-uturn-left" class="h-4 w-4 mr-1" />
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Profil Pelanggan</h2>
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                            {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'NA' }}
                        </div>
                        <div class="space-y-1">
                            <p class="text-lg font-semibold text-gray-900">{{ $user->name ?? 'Nama tidak tersedia' }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email ?? 'Email tidak tersedia' }}</p>
                            <p class="text-sm text-gray-500">No. Telepon: {{ $pelanggan->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Alamat</h2>
                    <p class="text-sm leading-relaxed text-gray-700">
                        {{ $pelanggan->alamat ?? 'Alamat belum diisi' }}
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @php
                        $isActive = $pelanggan->status === 'Aktif';
                        @endphp
                        <span
                            class="mt-1 inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $isActive ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-red-50 text-red-600 ring-red-500/20' }}">
                            <flux:icon name="{{ $isActive ? 'check-circle' : 'x-circle' }}" class="h-3 w-3" />
                            {{ $pelanggan->status ?? 'Tidak diketahui' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ optional($pelanggan->created_at)->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ optional($pelanggan->updated_at)->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Catatan</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Data pelanggan ini ditampilkan hanya untuk keperluan administrasi internal. Pastikan perubahan yang
                        dilakukan telah dikonfirmasi oleh pihak terkait.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
