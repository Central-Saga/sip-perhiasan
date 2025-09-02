<?php

use function Livewire\Volt\{ layout, title, state, computed };
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

layout('components.layouts.admin');
title('Role - Tambah');

state([
    'name' => '',
    'guard_name' => 'web',
    'permissions' => [],
]);

$save = function () {
    try {
        Log::channel('role_management')->info('Role Creation Started', [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
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
                Rule::unique('roles', 'name')->where('guard_name', $this->guard_name)
            ],
            'guard_name' => 'required|string',
            'permissions' => 'array',
        ]);

        Log::channel('role_management')->info('Role Validation Passed', [
            'validated_data' => $validated
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'],
        ]);

        Log::channel('role_management')->info('Role Created', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'guard_name' => $role->guard_name
        ]);

        // Assign permissions to role
        if (!empty($this->permissions)) {
            Log::channel('permissions')->info('Assigning Permissions to New Role', [
                'role_id' => $role->id,
                'permissions' => $this->permissions
            ]);

            $syncResult = $role->syncPermissions($this->permissions);

            Log::channel('permissions')->info('Permissions Assigned to New Role', [
                'role_id' => $role->id,
                'sync_result' => $syncResult
            ]);
        }

        session()->flash('success', 'Role berhasil ditambahkan!');
        return redirect()->route('role.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('role_management')->error('Role Creation Validation Failed', [
            'errors' => $e->errors(),
            'input_data' => [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'permissions' => $this->permissions
            ]
        ]);
        throw $e;

    } catch (\Exception $e) {
        Log::channel('role_management')->error('Role Creation Failed', [
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'input_data' => [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'permissions' => $this->permissions
            ]
        ]);

        session()->flash('error', 'Terjadi kesalahan saat menambahkan role: ' . $e->getMessage());
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
                    <flux:icon name="plus-circle" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Role Baru</h2>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Isi data role dengan benar untuk menambah ke daftar role.
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
                        <x-mary-input label="Nama Role" wire:model="name"
                            placeholder="Nama role (contoh: Manager, Staff)" icon="o-shield-check"
                            class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Nama role harus unik dan tidak boleh sama dengan role yang
                            sudah ada</p>
                        @error('name')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <x-mary-input label="Guard Name" wire:model="guard_name" placeholder="web" icon="o-lock-closed"
                            class="input-bordered" />
                        <p class="text-xs text-gray-500 mt-1">Biasanya menggunakan "web" untuk aplikasi web</p>
                        @error('guard_name')
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                            {{ $message }}
                        </x-mary-alert>
                        @enderror

                        <!-- Permissions Section -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Permissions</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-3">Pilih permissions yang akan diberikan ke role ini</p>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($this->allPermissions as $permission)
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                                        <x-mary-checkbox wire:model="permissions" value="{{ $permission->name }}" />
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
                            <x-mary-alert icon="o-exclamation-triangle" class="alert-error text-sm">
                                {{ $message }}
                            </x-mary-alert>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-3">
                        <x-mary-button label="Batal" href="{{ route('role.index') }}" class="btn-outline" />
                        <x-mary-button label="Simpan" type="submit" class="btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>