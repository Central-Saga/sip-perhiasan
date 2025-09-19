<?php

use function Livewire\Volt\{ layout, title, state, computed, mount };
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

layout('components.layouts.admin');
title('User - Edit');

state([
    'user' => null,
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
    'roles' => [],
]);

mount(function (User $user) {
    $this->user = $user;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->roles = $user->roles->pluck('name')->toArray();
});

$save = function () {
    try {
        Log::channel('user_management')->info('User Update Started', [
            'user_id' => $this->user->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles_count' => count($this->roles),
            'roles' => $this->roles,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $validationRules = [
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user->id)
            ],
            'roles' => 'array',
        ];

        // Only validate password if it's provided
        if (!empty($this->password)) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
            $validationRules['password_confirmation'] = 'required|string|min:8';
        }

        $validated = $this->validate($validationRules);

        Log::channel('user_management')->info('User Validation Passed', [
            'user_id' => $this->user->id,
            'validated_data' => collect($validated)->except(['password', 'password_confirmation'])
        ]);

        // Update user data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $this->user->update($updateData);

        Log::channel('user_management')->info('User Updated', [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email
        ]);

        // Update roles
        Log::channel('user_management')->info('Updating User Roles', [
            'user_id' => $this->user->id,
            'new_roles' => $this->roles
        ]);

        $this->user->syncRoles($this->roles);

        Log::channel('user_management')->info('User Roles Updated', [
            'user_id' => $this->user->id,
            'assigned_roles' => $this->user->roles->pluck('name')->toArray()
        ]);

        session()->flash('success', 'User berhasil diperbarui!');
        return redirect()->route('user.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('user_management')->error('User Update Validation Failed', [
            'user_id' => $this->user->id,
            'errors' => $e->errors(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('user_management')->error('User Update Failed', [
            'user_id' => $this->user->id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memperbarui user: ' . $e->getMessage());
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
                    <flux:icon name="pencil-square" class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Perbarui data user: <span class="font-medium text-blue-600">{{ $this->user->name }}</span>
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

                        <!-- Password Baru -->
                        <x-mary-input label="Password Baru" wire:model="password" placeholder="Minimal 8 karakter"
                            icon="o-lock-closed" type="password" class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter. Kosongkan jika tidak ingin
                            mengubah password</p>
                        @error('password')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Konfirmasi Password Baru -->
                        <x-mary-input label="Konfirmasi Password Baru" wire:model="password_confirmation"
                            placeholder="Ulangi password baru" icon="o-lock-closed" type="password"
                            class="input-bordered" />
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

                        <!-- Current User Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <flux:icon name="information-circle" class="h-5 w-5 text-blue-600" />
                                <span class="text-sm font-medium text-blue-800">Informasi User</span>
                            </div>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><span class="font-medium">ID:</span> {{ $this->user->id }}</p>
                                <p><span class="font-medium">Dibuat:</span> {{ $this->user->created_at->format('d M Y
                                    H:i') }}</p>
                                <p><span class="font-medium">Diperbarui:</span> {{ $this->user->updated_at->format('d M
                                    Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" href="{{ route('user.index') }}" class="btn-outline" />
                        <x-mary-button label="Perbarui" type="submit" class="btn-primary" />
                    </div>
                </form>
            </x-mary-card>
        </div>
    </div>
</div>