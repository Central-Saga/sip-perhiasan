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

            <!-- Form Card -->
            <div class="bg-white/90 backdrop-blur-xl border border-gray-200 shadow-xl rounded-2xl p-8">
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-blue-400">
                                <flux:icon name="user" class="h-5 w-5" />
                            </span>
                            <input id="name" type="text"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all duration-200"
                                wire:model="name" placeholder="Nama lengkap user" />
                            <p class="text-xs text-gray-500 mt-1">Masukkan nama lengkap user</p>
                            @error('name')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-blue-400">
                                <flux:icon name="envelope" class="h-5 w-5" />
                            </span>
                            <input id="email" type="email"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all duration-200"
                                wire:model="email" placeholder="user@example.com" />
                            <p class="text-xs text-gray-500 mt-1">Email harus unik dan akan digunakan untuk login</p>
                            @error('email')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru
                                <span class="text-xs text-gray-500 font-normal ml-1">(Kosongkan jika tidak ingin
                                    mengubah)</span>
                            </label>
                            <span class="absolute left-3 top-9 text-blue-400">
                                <flux:icon name="lock-closed" class="h-5 w-5" />
                            </span>
                            <input id="password" type="password"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all duration-200"
                                wire:model="password" placeholder="Minimal 8 karakter" />
                            <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter. Kosongkan jika tidak
                                ingin mengubah password</p>
                            @error('password')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(!empty($password))
                        <div class="relative">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-blue-400">
                                <flux:icon name="lock-closed" class="h-5 w-5" />
                            </span>
                            <input id="password_confirmation" type="password"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all duration-200"
                                wire:model="password_confirmation" placeholder="Ulangi password baru" />
                            <p class="text-xs text-gray-500 mt-1">Masukkan password yang sama untuk konfirmasi</p>
                            @error('password_confirmation')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <!-- Roles Section -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Role
                                <span class="text-xs text-gray-500 font-normal ml-1">(Pilih role yang akan diberikan ke
                                    user ini)</span>
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($this->allRoles as $role)
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                                        <x-checkbox wire:model="roles" value="{{ $role->name }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                                        <span class="ml-3 text-sm text-gray-700">{{ $role->name }}</span>
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
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
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
                        <x-button.link href="{{ route('user.index') }}"
                            class="px-5 py-2 text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 rounded-lg font-medium transition-all duration-150">
                            {{ __('Batal') }}
                        </x-button.link>
                        <x-button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition-all duration-150">
                            {{ __('Perbarui') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>