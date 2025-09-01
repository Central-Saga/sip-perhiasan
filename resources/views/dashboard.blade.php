<x-layouts.admin :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon name="shopping-cart" class="h-6 w-6 text-green-600 dark:text-green-300" />
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Transaksi Terbaru</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-300">
                            <th class="py-2 text-left">Kode</th>
                            <th class="py-2 text-left">Pelanggan</th>
                            <th class="py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Transaksi::with(['pelanggan.user'])->latest()->limit(5)->get() as $trx)
                        <tr class="border-b border-gray-100 dark:border-zinc-800">
                            <td class="py-2">{{ $trx->kode_transaksi }}</td>
                            <td class="py-2">{{ $trx->pelanggan->user->name ?? '-' }}</td>
                            <td class="py-2">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Tabel Produk Terlaris (contoh: 5 produk dengan stok terbanyak) -->
            <div
                class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon name="cube" class="h-6 w-6 text-purple-600 dark:text-purple-300" />
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Produk Stok Terbanyak</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-300">
                            <th class="py-2 text-left">Nama Produk</th>
                            <th class="py-2 text-left">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Produk::orderByDesc('stok')->limit(5)->get() as $produk)
                        <tr class="border-b border-gray-100 dark:border-zinc-800">
                            <td class="py-2">{{ $produk->nama_produk }}</td>
                            <td class="py-2">{{ $produk->stok }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>