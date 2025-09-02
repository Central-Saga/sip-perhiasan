<?php

use function Livewire\Volt\{
    layout, title, state, mount, usesPagination, computed
};

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

layout('components.layouts.admin');
title('User Management');
usesPagination();

// ---- STATE ----
state([
    'search'         => '',
    'sortField'      => 'id',
    'sortDirection'  => 'asc',
    'page'           => 1,
    'showDeleteDialog' => false,
    'userToDelete'   => null,
]);

// ---- LIST USERS (COMPUTED) ----
// - roles: pakai with('roles') untuk menampilkan nama role
// - search: name, email
// - sorting: name, email, created_at, id
$users = computed(function () {
    return User::query()
        ->when($this->search, function ($q) {
            $term = '%' . $this->search . '%';
            $q->where(function ($x) use ($term) {
                $x->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        })
        ->with('roles')
        ->when(in_array($this->sortField, ['name', 'email', 'created_at', 'id']), function ($q) {
            $q->orderBy($this->sortField, $this->sortDirection);
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

$openDeleteDialog = function ($userId) {
    $this->userToDelete = $userId;
    $this->showDeleteDialog = true;
};

$closeDeleteDialog = function () {
    $this->showDeleteDialog = false;
    $this->userToDelete = null;
};

$delete = function ($id) {
    try {
        Log::channel('user_management')->info('User Deletion Started', [
            'user_id' => $id,
            'admin_id' => auth()->id(),
            'timestamp' => now()
        ]);

        $user = User::find($id);

        if (!$user) {
            Log::channel('user_management')->warning('User Not Found for Deletion', [
                'user_id' => $id
            ]);
            session()->flash('error', 'User tidak ditemukan.');
            $this->closeDeleteDialog();
            return;
        }

        // Cegah hapus user yang sedang login
        if ($user->id === auth()->id()) {
            Log::channel('user_management')->warning('Self Deletion Attempted', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            $this->closeDeleteDialog();
            return;
        }

        $user->delete();

        Log::channel('user_management')->info('User Successfully Deleted', [
            'user_id' => $id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'deleted_at' => now()
        ]);

        session()->flash('message', "User '{$user->name}' berhasil dihapus.");
        $this->closeDeleteDialog();

    } catch (\Exception $e) {
        Log::channel('user_management')->error('User Deletion Failed', [
            'user_id' => $id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString()
        ]);

        session()->flash('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        $this->closeDeleteDialog();
    }
};

?>

<div class="max-w-full">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="users" class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Manajemen User</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola pengguna sistem
                    </p>
                </div>
            </div>
            <a href="{{ route('user.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                Tambah User
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
                    class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm transition-all duration-200"
                    placeholder="Cari nama, email, atau role..." />
            </div>
        </div>

        <!-- Stats -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total User</p>
                    <p class="text-2xl font-semibold text-blue-600">{{ $this->users->total() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <flux:icon name="users" class="h-6 w-6 text-blue-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $this->users->currentPage() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="view-columns" class="h-6 w-6 text-indigo-600" />
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
                                <flux:icon name="user" class="h-4 w-4" />
                                <span>Nama</span>
                                @if($sortField === 'name')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('email')">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="envelope" class="h-4 w-4" />
                                <span>Email</span>
                                @if($sortField === 'email')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                    class="h-4 w-4" />
                                @endif
                            </div>
                        </th>

                        <th class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="shield-check" class="h-4 w-4" />
                                <span>Role</span>
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
                    @forelse ($this->users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-4 py-4 text-sm text-gray-500 text-center">
                            {{ ($this->users->currentPage()-1) * $this->users->perPage() + $index + 1 }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-blue-700">{{ strtoupper(substr($user->name, 0,
                                        2)) }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm text-gray-700">{{ $user->email }}</span>
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                    <flux:icon name="shield-check" class="h-3 w-3" />
                                    {{ $role->name }}
                                </span>
                                @empty
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-500/10">
                                    <flux:icon name="minus" class="h-3 w-3" />
                                    Tidak ada role
                                </span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-700">
                            {{ optional($user->created_at)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="px-4 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('user.edit', $user) }}"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                    wire:navigate>
                                    <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                    Edit
                                </a>

                                @if($user->id !== auth()->id())
                                <button wire:click="openDeleteDialog({{ $user->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                    <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                    Hapus
                                </button>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                                    <flux:icon name="user" class="h-4 w-4 mr-1" />
                                    Anda
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8">
                            <div class="flex flex-col items-center justify-center">
                                <div class="relative">
                                    <div
                                        class="h-24 w-24 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-full flex items-center justify-center animate-pulse">
                                        <flux:icon name="users" class="h-12 w-12 text-gray-400" />
                                    </div>
                                    <div
                                        class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                        <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                    </div>
                                </div>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada user</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan user baru</p>
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
        <div class="bg-white px-4 py-3 flex items-center justify-between border border-gray-200 rounded-lg sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $this->users->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 flex items-center space-x-1">
                        <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                        <span>Menampilkan</span>
                        <span class="font-medium">{{ $this->users->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-medium">{{ $this->users->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-medium">{{ $this->users->total() }}</span>
                        <span>hasil</span>
                    </p>
                </div>
                <div>
                    {{ $this->users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Simple but Beautiful Delete Modal -->
    @if($showDeleteDialog)
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;"
        wire:click="closeDeleteDialog">
        <div style="background: white; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 400px; width: 90%; margin: 20px;"
            wire:click.stop>

            <!-- Header -->
            <div
                style="background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%); padding: 24px; border-bottom: 1px solid #fecaca; border-radius: 16px 16px 0 0;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="position: relative;">
                        <div
                            style="width: 56px; height: 56px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);">
                            <flux:icon name="exclamation-triangle" style="width: 28px; height: 28px; color: white;" />
                        </div>
                        <div
                            style="position: absolute; top: -4px; right: -4px; width: 20px; height: 20px; background: #f87171; border-radius: 50%; animation: pulse 2s infinite;">
                        </div>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">Konfirmasi
                            Hapus User</h3>
                        <p style="font-size: 14px; color: #6b7280; margin: 0;">Tindakan yang tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div style="padding: 24px;">
                <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <flux:icon name="information-circle"
                            style="width: 20px; height: 20px; color: #d97706; margin-top: 2px; flex-shrink: 0;" />
                        <div>
                            <p style="color: #92400e; font-weight: 600; font-size: 14px; margin: 0 0 4px 0;">⚠️
                                Peringatan!</p>
                            <p style="color: #b45309; font-size: 14px; line-height: 1.5; margin: 0;">
                                Apakah Anda yakin ingin menghapus user ini? Semua data terkait akan dihapus secara
                                permanen dan tidak dapat dipulihkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="padding: 0 24px 24px 24px;">
                <div style="display: flex; gap: 12px;">
                    <button type="button" wire:click="closeDeleteDialog"
                        style="flex: 1; padding: 12px 16px; font-size: 14px; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                        Batal
                    </button>
                    <button type="button" wire:click="delete({{ $userToDelete }})"
                        style="flex: 1; padding: 12px 16px; font-size: 14px; font-weight: 500; color: white; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3); transition: all 0.2s;"
                        onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 10px 15px -3px rgba(239, 68, 68, 0.4)'"
                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px -1px rgba(239, 68, 68, 0.3)'">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
    @endif
</div>