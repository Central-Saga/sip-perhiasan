<x-layouts.admin :title="__('Dashboard')">
    <div class="max-w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm">
                        <flux:icon name="home" class="h-8 w-8 text-blue-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Dashboard</h2>
                        <p class="mt-1 text-sm text-gray-600 flex items-center">
                            <flux:icon name="information-circle" class="h-4 w-4 mr-1 text-gray-400" />
                            Ringkasan data dan statistik sistem
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Terakhir diupdate</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="p-3 bg-white rounded-lg shadow-sm">
                        <flux:icon name="clock" class="h-6 w-6 text-gray-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Pelanggan -->
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                    <flux:icon name="users" class="h-8 w-8 text-indigo-600 dark:text-indigo-300" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-indigo-700 dark:text-indigo-200">{{ \App\Models\Pelanggan::count()
                        }}</p>
                </div>
            </div>
            <!-- Total Produk -->
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-900 rounded-lg">
                    <flux:icon name="cube" class="h-8 w-8 text-purple-600 dark:text-purple-300" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Total Produk</p>
                    <p class="text-2xl font-bold text-purple-700 dark:text-purple-200">{{ \App\Models\Produk::count() }}
                    </p>
                </div>
            </div>
            <!-- Total Transaksi -->
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                <div class="p-3 bg-green-50 dark:bg-green-900 rounded-lg">
                    <flux:icon name="shopping-cart" class="h-8 w-8 text-green-600 dark:text-green-300" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Total Transaksi</p>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-200">{{ \App\Models\Transaksi::count()
                        }}</p>
                </div>
            </div>
            <!-- Total Custom Request -->
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <flux:icon name="cube" class="h-8 w-8 text-blue-600 dark:text-blue-300" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Total Custom Request</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-200">{{ \App\Models\CustomRequest::count()
                        }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Data Table Preview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Tabel Transaksi Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <flux:icon name="shopping-cart" class="h-6 w-6 text-green-600" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">Transaksi Terbaru</h3>
                            <p class="text-sm text-gray-600">5 transaksi terakhir</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="hashtag" class="h-4 w-4" />
                                        <span>Kode</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="user" class="h-4 w-4" />
                                        <span>Pelanggan</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="currency-dollar" class="h-4 w-4" />
                                        <span>Total</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(\App\Models\Transaksi::with(['pelanggan.user'])->latest()->limit(5)->get() as $trx)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $trx->kode_transaksi }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $trx->pelanggan->user->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <flux:icon name="shopping-cart" class="h-12 w-12 text-gray-300 mb-2" />
                                        <p class="text-sm text-gray-500">Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Produk Stok Terbanyak -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <flux:icon name="cube" class="h-6 w-6 text-purple-600" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">Produk Stok Terbanyak</h3>
                            <p class="text-sm text-gray-600">5 produk dengan stok tertinggi</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="tag" class="h-4 w-4" />
                                        <span>Nama Produk</span>
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <flux:icon name="archive-box" class="h-4 w-4" />
                                        <span>Stok</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(\App\Models\Produk::orderByDesc('stok')->limit(5)->get() as $produk)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $produk->nama_produk }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $produk->stok }} unit
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <flux:icon name="cube" class="h-12 w-12 text-gray-300 mb-2" />
                                        <p class="text-sm text-gray-500">Belum ada produk</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>