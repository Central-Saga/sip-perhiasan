<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pelanggan Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit="save">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="no_telepon" value="Nomor Telepon" />
                                <x-input id="no_telepon" type="text" class="mt-1 block w-full" wire:model="no_telepon" />
                                <x-input-error for="no_telepon" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="alamat" value="Alamat" />
                                <x-textarea id="alamat" class="mt-1 block w-full" wire:model="alamat" />
                                <x-input-error for="alamat" class="mt-2" />
                            </div>

                            <div class="col-span-6">
                                <label class="flex items-center">
                                    <x-checkbox wire:model="status" />
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Aktif') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-button.link href="{{ route('pelanggan.index') }}" class="mr-3">
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
</x-app-layout>
