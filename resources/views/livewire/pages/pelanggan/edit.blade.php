<?php

use function Livewire\Volt\{ layout, title, state, mount, computed };
use App\Models\Pelanggan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

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
    try {
        $pelanggan = Pelanggan::with('user')->findOrFail($this->pelangganId);
        $userId = $pelanggan->user_id;

        // Log awal proses update
        Log::channel('pelanggan_management')->info('Pelanggan Update Started', [
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $userId,
            'current_name' => $pelanggan->user->name ?? 'N/A',
            'current_email' => $pelanggan->user->email ?? 'N/A',
            'new_name' => $this->name,
            'new_email' => $this->email,
            'new_phone' => $this->no_telepon,
            'new_status' => $this->status,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'no_telepon' => ['required', 'string', 'min:8', 'max:20'],
            'alamat' => ['required', 'string', 'min:10'],
            'status' => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        Log::channel('pelanggan_management')->info('Pelanggan Validation Passed', [
            'pelanggan_id' => $pelanggan->id,
            'validated_data' => $validated
        ]);

        // Update user data
        if ($pelanggan->user) {
            $pelanggan->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            Log::channel('pelanggan_management')->info('User Data Updated', [
                'pelanggan_id' => $pelanggan->id,
                'user_id' => $userId,
                'updated_name' => $validated['name'],
                'updated_email' => $validated['email']
            ]);
        }

        // Update pelanggan data
        $pelanggan->update([
            'no_telepon' => $validated['no_telepon'],
            'alamat' => $validated['alamat'],
            'status' => $validated['status'],
        ]);

        Log::channel('pelanggan_management')->info('Pelanggan Data Updated', [
            'pelanggan_id' => $pelanggan->id,
            'updated_phone' => $validated['no_telepon'],
            'updated_address' => $validated['alamat'],
            'updated_status' => $validated['status']
        ]);

        Log::channel('pelanggan_management')->info('Pelanggan Update Completed Successfully', [
            'pelanggan_id' => $pelanggan->id,
            'user_name' => $validated['name'],
            'updated_at' => now()
        ]);

        session()->flash('success', 'Data pelanggan berhasil diperbarui.');
        return redirect()->route('pelanggan.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('pelanggan_management')->error('Pelanggan Update Validation Failed', [
            'pelanggan_id' => $this->pelangganId,
            'errors' => $e->errors(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'no_telepon' => $this->no_telepon,
                'alamat' => $this->alamat,
                'status' => $this->status
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('pelanggan_management')->error('Pelanggan Update Failed', [
            'pelanggan_id' => $this->pelangganId,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'no_telepon' => $this->no_telepon,
                'alamat' => $this->alamat,
                'status' => $this->status
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memperbarui pelanggan: ' . $e->getMessage());
        return;
    }
};

// Get status options for the form
$statusOptions = computed(function () {
    return [
        ['id' => 'Aktif', 'name' => 'Aktif', 'hint' => 'Pelanggan aktif dapat melakukan transaksi'],
        ['id' => 'Tidak Aktif', 'name' => 'Tidak Aktif', 'hint' => 'Pelanggan tidak aktif tidak dapat melakukan transaksi'],
    ];
});
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
                        <x-mary-input label="Nama Lengkap" wire:model="name" placeholder="Nama lengkap pelanggan"
                            icon="o-user" class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Nama harus minimal 3 karakter</p>
                        @error('name')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <x-mary-input label="Alamat Email" wire:model="email" placeholder="contoh@email.com"
                            icon="o-envelope" class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Email harus unik dan valid</p>
                        @error('email')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <x-mary-input label="Nomor Telepon" wire:model="no_telepon" placeholder="08xxxxxxxxxx"
                            icon="o-phone" class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Nomor telepon minimal 8 digit</p>
                        @error('no_telepon')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <x-mary-textarea label="Alamat Lengkap" wire:model="alamat"
                            placeholder="Alamat lengkap pelanggan..." icon="o-map-pin" class="textarea-bordered"
                            rows="3" />
                        <p class="text-xs text-gray-500 mt-1">Alamat minimal 10 karakter</p>
                        @error('alamat')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Status Section -->
                        <x-mary-radio label="Status Pelanggan" wire:model="status" :options="$this->statusOptions"
                            inline />
                        <p class="text-xs text-gray-500 mt-1">Pilih status pelanggan</p>
                        @error('status')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" href="{{ route('pelanggan.index') }}" class="btn-outline" />
                        <x-mary-button label="Perbarui" type="submit" class="btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>