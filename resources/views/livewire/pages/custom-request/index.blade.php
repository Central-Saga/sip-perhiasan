
<?php
use Livewire\Volt\Component;
use App\Models\CustomRequest;

new class extends Component {
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function getCustomRequestsProperty()
    {
        return CustomRequest::query()
            ->with(['pelanggan.user'])
            ->when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('kategori', 'like', '%' . $this->search . '%')
                    ->orWhere('berat', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelanggan.user', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function delete($id)
    {
        $request = CustomRequest::findOrFail($id);
        $request->delete();
        // No need to refresh, pagination will auto update
    }
};
?>

<div>
<div class="max-w-full">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <flux:icon name="sparkles" class="h-8 w-8 text-indigo-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Data Custom Request</h2>
                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                        <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                        Kelola permintaan kustom perhiasan dari pelanggan
                    </p>
                </div>
            </div>
            <a href="{{ route('custom-request.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <flux:icon name="plus-circle" class="h-5 w-5 mr-1.5" />
                Tambah Custom Request
            </a>
        </div>
    </div>

    <!-- Search and Stats Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        <!-- Search Bar -->
        <div class="lg:col-span-2">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <input wire:model.live="search" 
                    type="search" 
                    class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all duration-200"
                    placeholder="Cari berdasarkan deskripsi, kategori, atau pelanggan..." />
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Request</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $this->customRequests->total() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <flux:icon name="document-text" class="h-6 w-6 text-indigo-600" />
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Halaman</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ $this->customRequests->currentPage() }}</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <flux:icon name="view-columns" class="h-6 w-6 text-purple-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="hashtag" class="h-4 w-4" />
                                <span>No</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="calendar" class="h-4 w-4" />
                                <span>Tanggal</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="user-circle" class="h-4 w-4" />
                                <span>Pelanggan</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="document-text" class="h-4 w-4" />
                                <span>Deskripsi</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="tag" class="h-4 w-4" />
                                <span>Kategori</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="scale" class="h-4 w-4" />
                                <span>Berat</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <flux:icon name="currency-dollar" class="h-4 w-4" />
                                <span>Harga</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-end space-x-1">
                                <flux:icon name="cog" class="h-4 w-4" />
                                <span>Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->customRequests as $customRequest)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $customRequest->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        {{ $customRequest->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ strtoupper(substr($customRequest->pelanggan->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $customRequest->pelanggan->user->name }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 max-w-xs">
                                <div class="relative group">
                                    <p class="text-sm text-gray-900 truncate">
                                        {{ $customRequest->deskripsi }}
                                    </p>
                                    <div class="hidden group-hover:block absolute z-10 p-2 bg-gray-900 text-white text-xs rounded-lg mt-1 max-w-xs">
                                        {{ $customRequest->deskripsi }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                    <flux:icon name="tag" class="h-3 w-3" />
                                    {{ $customRequest->kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-700/10">
                                    <flux:icon name="scale" class="h-3 w-3" />
                                    {{ number_format($customRequest->berat, 2) }} gr
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                    <flux:icon name="currency-dollar" class="h-3 w-3" />
                                    Rp {{ number_format($customRequest->estimasi_harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('custom-request.edit', $customRequest) }}" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-150"
                                        wire:navigate>
                                        <flux:icon name="pencil-square" class="h-4 w-4 mr-1" />
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $customRequest->id }})" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                        wire:confirm="Apakah anda yakin ingin menghapus data ini?">
                                        <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative">
                                        <div class="h-24 w-24 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-full flex items-center justify-center animate-pulse">
                                            <flux:icon name="inbox" class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <div class="absolute -right-2 -bottom-2 h-8 w-8 bg-gray-50 rounded-full flex items-center justify-center border-2 border-white">
                                            <flux:icon name="plus" class="h-5 w-5 text-gray-400" />
                                        </div>
                                    </div>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada custom request</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan request baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-6">
        <div class="bg-white px-4 py-3 flex items-center justify-between border border-gray-200 rounded-lg sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $this->customRequests->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 flex items-center space-x-1">
                        <flux:icon name="document-text" class="h-4 w-4 text-gray-400" />
                        <span>Menampilkan</span>
                        <span class="font-medium">{{ $this->customRequests->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-medium">{{ $this->customRequests->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-medium">{{ $this->customRequests->total() }}</span>
                        <span>hasil</span>
                    </p>
                </div>
                <div>
                    {{ $this->customRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
