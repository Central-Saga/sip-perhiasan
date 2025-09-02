<?php

use function Livewire\Volt\{ layout, title, state, computed };
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

layout('components.layouts.admin');
title('User - Tambah');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
    'roles' => [],
]);

$save = function () {
    try {
        Log::channel('user_management')->info('User Creation Started', [
            'name' => $this->name,
            'email' => $this->email,
            'roles_count' => count($this->roles),
            'roles' => $this->roles,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $validated = $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'roles' => 'array',
        ]);

        Log::channel('user_management')->info('User Validation Passed', [
            'validated_data' => collect($validated)->except(['password', 'password_confirmation'])
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Log::channel('user_management')->info('User Created', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email
        ]);

        // Assign roles to user
        if (!empty($this->roles)) {
            Log::channel('user_management')->info('Assigning Roles to New User', [
                'user_id' => $user->id,
                'roles' => $this->roles
            ]);

            $user->syncRoles($this->roles);

            Log::channel('user_management')->info('Roles Assigned to New User', [
                'user_id' => $user->id,
                'assigned_roles' => $user->roles->pluck('name')->toArray()
            ]);
        }

        session()->flash('success', 'User berhasil ditambahkan!');
        return redirect()->route('user.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('user_management')->error('User Creation Validation Failed', [
            'errors' => $e->errors(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('user_management')->error('User Creation Failed', [
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat menambahkan user: ' . $e->getMessage());
        return;
    }
};

// Get all roles for the form
$allRoles = computed(function () {
    return Role::all();
});
?>

<div>
    <div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="plus-circle" class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah User Baru</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Isi data user dengan benar untuk menambah ke daftar pengguna.
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

            <!-- Form Card dengan Mary UI -->
            <x-mary-card class="bg-white/90 backdrop-blur-xl shadow-xl p-8">
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nama Lengkap -->
                        <x-mary-input label="Nama Lengkap" wire:model="name" placeholder="Nama lengkap user"
                            icon="o-user" class="input-bordered" />
                        @error('name')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Email -->
                        <x-mary-input label="Email" wire:model="email" placeholder="user@example.com" icon="o-envelope"
                            type="email" class="input-bordered" />
                        @error('email')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Password -->
                        <x-mary-input label="Password" wire:model="password" placeholder="Minimal 8 karakter"
                            icon="o-lock-closed" type="password" class="input-bordered" />
                        @error('password')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Konfirmasi Password -->
                        <x-mary-input label="Konfirmasi Password" wire:model="password_confirmation"
                            placeholder="Ulangi password" icon="o-lock-closed" type="password" class="input-bordered" />
                        @error('password_confirmation')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Roles Section -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Role</span>
                                <span class="label-text-alt text-xs text-gray-500">Pilih role yang akan diberikan ke
                                    user ini</span>
                            </label>
                            <div class="bg-base-100 border border-base-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($this->allRoles as $role)
                                    <label
                                        class="flex items-center p-2 hover:bg-base-200 rounded-lg transition-colors duration-150 cursor-pointer">
                                        <input type="checkbox" wire:model="roles" value="{{ $role->name }}"
                                            class="checkbox checkbox-info" />
                                        <span class="ml-3 text-sm">{{ $role->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @if($this->allRoles->isEmpty())
                                <div class="text-center py-8">
                                    <flux:icon name="exclamation-triangle"
                                        class="h-12 w-12 text-gray-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500">Belum ada role tersedia</p>
                                </div>
                                @endif
                            </div>
                            @error('roles')
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" href="{{ route('user.index') }}" class="btn-outline" />
                        <x-mary-button label="Simpan" type="submit" class="btn-primary" />
                    </div>
                </form>
            </x-mary-card>
        </div>
    </div>
</div>