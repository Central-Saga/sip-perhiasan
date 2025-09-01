<?php

use function Livewire\Volt\{ layout, title, state, computed, mount };
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

layout('components.layouts.admin');
title('Role - Edit');

state([
    'name' => '',
    'guard_name' => 'web',
    'permissions' => [],
    'role_id' => null,
]);

mount(function (Role $role) {
    $this->role_id = $role->id;
    $this->name = $role->name;
    $this->guard_name = $role->guard_name;
    $this->permissions = $role->permissions->pluck('name')->toArray();
});

$save = function () {
    try {
        $role = Role::findOrFail($this->role_id);

        // Log awal proses update
        Log::channel('role_management')->info('Role Update Started', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'new_name' => $this->name,
            'new_guard_name' => $this->guard_name,
            'permissions_count' => count($this->permissions),
            'permissions' => $this->permissions,
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                Rule::unique('roles', 'name')->where('guard_name', $this->guard_name)->ignore($role->id)
            ],
            'guard_name' => 'required|string',
            'permissions' => 'array',
        ]);

        Log::channel('role_management')->info('Role Validation Passed', [
            'role_id' => $role->id,
            'validated_data' => $validated
        ]);

        // Update role basic info
        $role->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'],
        ]);

        Log::channel('role_management')->info('Role Basic Info Updated', [
            'role_id' => $role->id,
            'updated_name' => $role->name,
            'updated_guard_name' => $role->guard_name
        ]);

        // Log permissions sebelum sync
        $currentPermissions = $role->permissions->pluck('name')->toArray();
        Log::channel('permissions')->info('Permissions Before Sync', [
            'role_id' => $role->id,
            'current_permissions' => $currentPermissions,
            'new_permissions' => $this->permissions
        ]);

        // Sync permissions to role
        $syncResult = $role->syncPermissions($this->permissions);

        Log::channel('permissions')->info('Permissions Sync Completed', [
            'role_id' => $role->id,
            'sync_result' => $syncResult,
            'attached' => $syncResult['attached'] ?? [],
            'detached' => $syncResult['detached'] ?? [],
            'updated' => $syncResult['updated'] ?? []
        ]);

        // Verifikasi permissions setelah sync
        $role->refresh();
        $finalPermissions = $role->permissions->pluck('name')->toArray();

        Log::channel('permissions')->info('Final Permissions Verification', [
            'role_id' => $role->id,
            'final_permissions' => $finalPermissions,
            'expected_permissions' => $this->permissions,
            'permissions_match' => $finalPermissions === $this->permissions
        ]);

        session()->flash('success', 'Role berhasil diperbarui!');
        return redirect()->route('role.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('role_management')->error('Role Update Validation Failed', [
            'role_id' => $this->role_id,
            'errors' => $e->errors(),
            'input_data' => [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'permissions' => $this->permissions
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('role_management')->error('Role Update Failed', [
            'role_id' => $this->role_id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'permissions' => $this->permissions
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memperbarui role: ' . $e->getMessage());
        return;
    }
};

// Get all permissions for the form
$allPermissions = computed(function () {
    return Permission::all();
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
                    <h2 class="text-2xl font-bold text-gray-900">Edit Role</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Perbarui data role dan permissions yang ada.
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
                                Nama Role <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="shield-check" class="h-5 w-5" />
                            </span>
                            <input id="name" type="text"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200"
                                wire:model="name" placeholder="Nama role (contoh: Manager, Staff)" />
                            <p class="text-xs text-gray-500 mt-1">Nama role harus unik dan tidak boleh sama dengan role
                                yang sudah ada</p>
                            @error('name')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="guard_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Guard Name <span class="text-red-500">*</span>
                            </label>
                            <span class="absolute left-3 top-9 text-indigo-400">
                                <flux:icon name="lock-closed" class="h-5 w-5" />
                            </span>
                            <input id="guard_name" type="text"
                                class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all duration-200"
                                wire:model="guard_name" placeholder="web" />
                            <p class="text-xs text-gray-500 mt-1">Biasanya menggunakan "web" untuk aplikasi web</p>
                            @error('guard_name')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Permissions Section -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Permissions
                                <span class="text-xs text-gray-500 font-normal ml-1">(Pilih permissions yang akan
                                    diberikan ke role ini)</span>
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($this->allPermissions as $permission)
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                                        <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                        <span class="ml-3 text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @if($this->allPermissions->isEmpty())
                                <div class="text-center py-8">
                                    <flux:icon name="exclamation-triangle"
                                        class="h-12 w-12 text-gray-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500">Belum ada permissions tersedia</p>
                                </div>
                                @endif
                            </div>
                            @error('permissions')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-button.link href="{{ route('role.index') }}"
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