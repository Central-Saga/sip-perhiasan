<?php

use App\Models\CustomRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, with};

state([
    'search' => '',
    'sortField' => 'created_at',
    'sortDirection' => 'desc',
]);

with(function() {
    return [
        'customRequests' => CustomRequest::query()
            ->with('pelanggan') // Eager load the pelanggan relationship
            ->when($this->search, function ($query) {
                $query->where('kode_request', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('jenis', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelanggan', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10)
    ];
});

$delete = function($id) {
    $request = CustomRequest::findOrFail($id);
    $request->delete();
};

?>

<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-x-3">
                    <flux:icon name="sparkles" class="w-8 h-8 text-indigo-600" />
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Data Custom Request</h1>
                        <p class="mt-2 text-sm text-gray-700">Kelola permintaan kustom perhiasan dari pelanggan
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('custom-request.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <flux:icon name="plus-circle" class="w-5 h-5 mr-2" /> Tambah Custom Request
                </a>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <x-input type="search" wire:model.live="search" class="pl-10 w-full"
                    placeholder="Cari kode request, status, atau pelanggan..." />
            </div>
            <div class="flex items-center gap-x-4">
                <div
                    class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                    <flux:icon name="sparkles" class="w-5 h-5 text-indigo-500" />
                    <span class="font-medium">Total: {{ $customRequests->total() }} Request</span>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white/50 backdrop-blur-xl rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    <flux:icon name="hashtag" class="w-5 h-5 text-gray-400 mr-2" /> No
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="identification" class="w-5 h-5 text-gray-400 mr-2" />
                                    Kode Request
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="user" class="w-5 h-5 text-gray-400 mr-2" /> Pelanggan
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="check-circle" class="w-5 h-5 text-gray-400 mr-2" /> Status
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="tag" class="w-5 h-5 text-gray-400 mr-2" /> Jenis
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="document-text" class="w-5 h-5 text-gray-400 mr-2" />
                                    Deskripsi
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="photo" class="w-5 h-5 text-gray-400 mr-2" /> Referensi
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <flux:icon name="cog" class="w-5 h-5 text-gray-400 mr-2" /> Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customRequests as $request)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $request->kode_request }}</td>
                                <td class="px-6 py-4">
                                    @if($request->pelanggan)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                                <flux:icon name="user" class="w-4 h-4 text-blue-600" />
                                            </div>
                                            <span>{{ $request->pelanggan->nama }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-medium inline-flex items-center space-x-1',
                                        'bg-orange-100 text-orange-800' => $request->status === 'PENDING',
                                        'bg-indigo-100 text-indigo-800' => $request->status === 'PROSES',
                                        'bg-teal-100 text-teal-800' => $request->status === 'SELESAI',
                                        'bg-red-100 text-red-800' => $request->status === 'DITOLAK',
                                    ])>
                                        <flux:icon name="{{ 
                                            $request->status === 'SELESAI' ? 'check-circle' : 
                                            ($request->status === 'PROSES' ? 'cog' :
                                            ($request->status === 'DITOLAK' ? 'x-circle' : 'clock'))
                                        }}" class="w-4 h-4" />
                                        <span>{{ $request->status }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                            <flux:icon name="sparkles" class="w-4 h-4 text-blue-600" />
                                        </div>
                                        <span>{{ $request->jenis }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-600">{{ Str::limit($request->deskripsi, 50) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($request->referensi)
                                        <a href="{{ Storage::url($request->referensi) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 text-sm text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                            <flux:icon name="photo" class="w-4 h-4 mr-1.5" /> Lihat Gambar
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('custom-request.edit', $request) }}"
                                            class="flex items-center px-3 py-1.5 text-sm text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-150"
                                            wire:navigate>
                                            <flux:icon name="pencil-square" class="w-4 h-4 mr-1.5" /> Edit
                                        </a>
                                        <button wire:click="delete({{ $request->id }})"
                                            class="flex items-center px-3 py-1.5 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                            wire:confirm="Apakah anda yakin ingin menghapus data ini?">
                                            <flux:icon name="trash" class="w-4 h-4 mr-1.5" /> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <flux:icon name="inbox" class="w-16 h-16 mb-4" />
                                        <span class="font-medium text-xl">Tidak ada request ditemukan...</span>
                                        <p class="text-sm mt-2">Silahkan tambahkan request baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500 italic">
                    <flux:icon name="information-circle" class="w-4 h-4 inline-block mr-1" />
                    Menampilkan {{ $customRequests->firstItem() ?? 0 }} hingga {{ $customRequests->lastItem() ?? 0 }}
                    dari {{ $customRequests->total() }} request
                </div>
                <div>
                    {{ $customRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>