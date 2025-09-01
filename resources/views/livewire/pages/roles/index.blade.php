<?php

use function Livewire\Volt\{
    layout, title, state, mount, usesPagination, computed
};

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

layout('components.layouts.admin');
title('Role Management');
usesPagination();

// ---- STATE ----
state([
    'search'         => '',
    'sortField'      => 'id',
    'sortDirection'  => 'asc',
    'page'           => 1,
    'protectedNames' => ['admin', 'manager', 'owner'], // role yang tidak boleh dihapus
    'showDeleteDialog' => false,
    'roleToDelete'   => null,
]);

// ---- LIST ROLES (COMPUTED) ----
// - users_count: pakai subquery COUNT di model_has_roles (tanpa N+1)
// - permissions_count: pakai withCount('permissions')
// - search: name / guard_name
// - sorting: name, guard_name, users_count, permissions_count, created_at, id
$roles = computed(function () {
    return Role::query()
        ->when($this->search, function ($q) {
            $term = '%' . $this->search . '%';
            $q->where(function ($x) use ($term) {
                $x->where('name', 'like', $term)
                  ->orWhere('guard_name', 'like', $term);
            });
        })
        ->select('roles.*')
        ->selectSub(function ($q) {
            $q->from('model_has_roles')
              ->whereColumn('model_has_roles.role_id', 'roles.id')
              ->where('model_type', User::class)
              ->selectRaw('COUNT(*)');
        }, 'users_count')
        ->withCount('permissions')
        ->when(in_array($this->sortField, ['name', 'guard_name', 'created_at', 'id']), function ($q) {
            $q->orderBy($this->sortField, $this->sortDirection);
        })
        ->when($this->sortField === 'users_count', function ($q) {
            $q->orderBy(DB::raw('users_count'), $this->sortDirection);
        })
        ->when($this->sortField === 'permissions_count', function ($q) {
            $q->orderBy('permissions_count', $this->sortDirection);
        })
        ->paginate(10);
});

// ---- ACTIONS ----
$sortBy = function (string $field) {
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
};

$openDeleteDialog = function ($roleId) {
    $this->roleToDelete = $roleId;
    $this->showDeleteDialog = true;
};

$closeDeleteDialog = function () {
    $this->showDeleteDialog = false;
    $this->roleToDelete = null;
};

$delete = function ($id) {
    try {
        Log::channel('role_management')->info('Role Deletion Started', [
            'role_id' => $id,
            'user_id' => auth()->id(),
            'timestamp' => now(),
            'protected_names' => $this->protectedNames
        ]);

        $role = Role::find($id);

        if (!$role) {
            Log::channel('role_management')->warning('Role Not Found for Deletion', [
                'role_id' => $id
            ]);
            session()->flash('error', 'Role tidak ditemukan.');
            $this->closeDeleteDialog();
            return;
        }

        Log::channel('role_management')->info('Role Found for Deletion', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'guard_name' => $role->guard_name,
            'is_protected' => in_array(strtolower($role->name), array_map('strtolower', $this->protectedNames), true),
            'protected_names' => $this->protectedNames
        ]);

        // Proteksi: nama role tertentu tidak boleh dihapus (case insensitive)
        if (in_array(strtolower($role->name), array_map('strtolower', $this->protectedNames), true)) {
            Log::channel('role_management')->warning('Protected Role Deletion Attempted', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'protected_names' => $this->protectedNames
            ]);
            session()->flash('error', "Role '{$role->name}' dilindungi dan tidak dapat dihapus.");
            $this->closeDeleteDialog();
            return;
        }

        // Cegah hapus jika masih terikat ke user
        $usersCount = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->where('model_type', User::class)
            ->count();

        Log::channel('role_management')->info('Role Users Count Check', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'users_count' => $usersCount,
            'can_delete' => $usersCount === 0
        ]);

        if ($usersCount > 0) {
            Log::channel('role_management')->warning('Role Deletion Blocked - Still in Use', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'users_count' => $usersCount
            ]);
            session()->flash('error', "Role '{$role->name}' masih dipakai oleh {$usersCount} pengguna. Lepas role dari user terlebih dahulu.");
            $this->closeDeleteDialog();
            return;
        }

        // Log permissions yang akan dihapus
        $permissions = $role->permissions->pluck('name')->toArray();
        Log::channel('permissions')->info('Role Permissions Before Deletion', [
            'role_id' => $role->id,
            'permissions' => $permissions
        ]);

        $role->delete();

        Log::channel('role_management')->info('Role Successfully Deleted', [
            'role_id' => $id,
            'role_name' => $role->name,
            'guard_name' => $role->guard_name,
            'deleted_permissions' => $permissions,
            'deleted_at' => now()
        ]);

                session()->flash('message', "Role '{$role->name}' berhasil dihapus.");

        // Tutup dialog setelah berhasil delete
        $this->closeDeleteDialog();

        // Data akan otomatis refresh karena computed property tanpa persist

    } catch (\Exception $e) {
        Log::channel('role_management')->error('Role Deletion Failed', [
            'role_id' => $id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString()
        ]);

        session()->flash('error', 'Terjadi kesalahan saat menghapus role: ' . $e->getMessage());
        $this->closeDeleteDialog();
    }
};

?>

<div class="max-w-full">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="shield-check" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Manajemen Role</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola role & perizinan (Spatie Permission)
                    </p>
                </div>
            </div>
            <a href="{{ route('role.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                Tambah Role
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
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

    @if (session()->has('message'))
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center">
        <flux:icon name="information-circle" class="h-5 w-5 mr-2" />
        {{ session('message') }}
    </div>
    @endif

    <!-- Search & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        <!-- Search -->
        <div class="lg:col-span-2">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <input wire:model.live="search" type="search"
                    class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all duration-200"
                    placeholder="Cari role atau guard name..." />
            </div>
        </div>

        <!-- Stats -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Role</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $this->roles->total() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="list-bullet" class="h-6 w-6 text-indigo-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ $this->roles->currentPage() }}</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <flux:icon name="view-columns" class="h-6 w-6 text-purple-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="hashtag" class="h-4 w-4" />
                                <span>No</span>
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('name')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="shield-check" class="h-4 w-4" />
                                <span>Role</span>
                                @if($sortField === 'name')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('guard_name')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="lock-closed" class="h-4 w-4" />
                                <span>Guard</span>
                                @if($sortField === 'guard_name')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('users_count')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="users" class="h-4 w-4" />
                                <span>Pengguna</span>
                                @if($sortField === 'users_count')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('permissions_count')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="key" class="h-4 w-4" />
                                <span>Permissions</span>
                                @if($sortField === 'permissions_count')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="calendar" class="h-4 w-4" />
                                <span>Dibuat</span>
                                @if($sortField === 'created_at')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-end space-x-1">
                                <flux:icon name="cog" class="h-4 w-4" />
                                <span>Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->roles as $index => $role)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-4 py-4 text-sm text-gray-500 text-center">
                            {{ ($this->roles->currentPage()-1) * $this->roles->perPage() + $index + 1 }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">{{ strtoupper(substr($role->name,
                                        0, 2)) }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $role->name }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                <flux:icon name="lock-closed" class="h-3 w-3" />
                                {{ $role->guard_name }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                <flux:icon name="users" class="h-3 w-3" />
                                {{ $role->users_count }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                <flux:icon name="key" class="h-3 w-3" />
                                {{ $role->permissions_count }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-700">
                            {{ optional($role->created_at)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="px-4 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('role.edit', $role) }}"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150"
                                    wire:navigate>
                                    <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                    Edit
                                </a>

                                @if(!in_array(strtolower($role->name), array_map('strtolower', $this->protectedNames)))
                                <button wire:click="openDeleteDialog({{ $role->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                    <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                    Hapus
                                </button>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                                    <flux:icon name="shield-check" class="h-4 w-4 mr-1" />
                                    Dilindungi
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8">
                            <div class="flex flex-col items-center justify-center">
                                <div class="relative">
                                    <div
                                        class="h-24 w-24 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse">
                                        <flux:icon name="shield-check" class="h-12 w-12 text-gray-400" />
                                    </div>
                                    <div
                                        class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                        <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                    </div>
                                </div>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada role</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan role baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">

        <!-- Enhance Pagination -->
        <div class="mt-6">
            <div class="bg-white px-4 py-3 flex items-center justify-between border border-gray-200 rounded-lg sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $this->roles->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 flex items-center space-x-1">
                            <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                            <span>Menampilkan</span>
                            <span class="font-medium">{{ $this->roles->firstItem() ?? 0 }}</span>
                            <span>sampai</span>
                            <span class="font-medium">{{ $this->roles->lastItem() ?? 0 }}</span>
                            <span>dari</span>
                            <span class="font-medium">{{ $this->roles->total() }}</span>
                            <span>hasil</span>
                        </p>
                    </div>
                    <div>
                        {{ $this->roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Delete Confirmation Dialog -->
    @if($showDeleteDialog)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 backdrop-blur-sm bg-white/30 transition-opacity" aria-hidden="true"
                wire:click="closeDeleteDialog"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <flux:icon name="exclamation-triangle" class="h-6 w-6 text-red-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Hapus Role
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus role ini? Pastikan role tidak terikat ke user
                                    manapun.
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="delete({{ $roleToDelete }})"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        <flux:icon name="trash" class="h-4 w-4 mr-2" />
                        Ya, Hapus
                    </button>
                    <button type="button" wire:click="closeDeleteDialog"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        <flux:icon name="x-mark" class="h-4 w-4 mr-2" />
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>