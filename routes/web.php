<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Volt\Volt;




Route::prefix('/')->group(function () {
    // LandingPage (baru)
    Volt::route('/', 'pages.landingpage.home.index')->name('home');
    Volt::route('about', 'pages.landingpage.aboutme.index')->name('about');
    Volt::route('produk', 'pages.landingpage.produk.index')->name('produk');
    // Alias name for nav compatibility
    Route::get('produk-landing', function () {
        return redirect()->route('produk');
    })->name('produk.landing');
    Volt::route('produk/{id}', 'pages.landingpage.produk.detail')->name('produk.detail');
    Volt::route('custom', 'pages.landingpage.custom.index')->name('custom');
    Volt::route('custom/detail', 'pages.landingpage.custom.detail')->name('custom.detail');
    Volt::route('custom/status', 'pages.landingpage.custom.status')->name('custom.status');

    // Handle Custom Request form submission (POST)
    Route::post('custom/submit', function (Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'material' => 'required|string|max:100',
            'ukuran' => 'nullable|string|max:255',
            'berat' => 'nullable|numeric|min:0',
            'deskripsi' => 'required|string',
            'gambar_referensi' => 'nullable|image|max:2048',
        ]);

        try {
            $user = Auth::user();
            $pelanggan = \App\Models\Pelanggan::where('user_id', $user->id)->first();

            if (!$pelanggan) {
                return back()->with('error', 'Data pelanggan tidak ditemukan');
            }

            // Allow multiple custom requests in cart
            // Removed the restriction that limited custom requests to only 1 per cart

            // Handle file upload
            $gambarPath = null;
            if ($request->hasFile('gambar_referensi')) {
                $gambarPath = $request->file('gambar_referensi')->store('custom-referensi', 'public');
            }

            // Create custom request
            $customRequest = \App\Models\CustomRequest::create([
                'pelanggan_id' => $pelanggan->id,
                'kategori' => $validated['kategori'],
                'material' => $validated['material'],
                'ukuran' => $validated['ukuran'] ?? '',
                'berat' => $validated['berat'] ?? 0,
                'deskripsi' => $validated['deskripsi'],
                'gambar_referensi' => $gambarPath,
                'status' => 'pending',
                'estimasi_harga' => 0,
            ]);

            return redirect()->route('custom.status')->with('success', 'Custom request berhasil dikirim! Tim kami akan segera menghubungi Anda untuk konsultasi dan penawaran harga.');
        } catch (\Exception $e) {
            \Log::error('Error saving custom request: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan custom request: ' . $e->getMessage());
        }
    })->name('custom.submit');
    Volt::route('cart', 'pages.landingpage.cart.index')->name('cart');

    // Cart count API
    Route::get('cart/count', function () {
        $count = 0;
        if (Auth::check()) {
            $pelanggan = Auth::user()->pelanggan;
            if ($pelanggan) {
                // Count both regular products and custom requests
                $count = \App\Models\Keranjang::where('pelanggan_id', $pelanggan->id)->sum('jumlah');
            }
        }
        return response()->json(['count' => $count]);
    })->name('cart.count');

    Volt::route('checkout', 'pages.landingpage.checkout.index')->name('checkout');
    Volt::route('transaksi', 'pages.landingpage.transaksi.index')->name('transaksi');
    Volt::route('transaksi/{id}', 'pages.landingpage.transaksi.detail')->name('transaksi.detail');
});

// Fallback ke landing bila URL tidak ditemukan
Route::fallback(function () {
    return redirect()->route('home');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'admin.access'])
    ->name('dashboard');

Route::middleware(['auth', 'admin.access'])->prefix('admin')->group(function () {
    // User Routes
    Volt::route('user', 'pages.users.index')->name('user.index');
    Volt::route('user/create', 'pages.users.create')->name('user.create');
    Volt::route('user/{user}/edit', 'pages.users.edit')->name('user.edit');

    // Role Routes
    Volt::route('role', 'pages.roles.index')->name('role.index');
    Volt::route('role/create', 'pages.roles.create')->name('role.create');
    Volt::route('role/{role}/edit', 'pages.roles.edit')->name('role.edit');

    // Settings Routes
    Route::redirect('settings', 'admin/settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Pelanggan Routes
    Volt::route('pelanggan', 'pages.pelanggan.index')->name('pelanggan.index');
    Volt::route('pelanggan/create', 'pages.pelanggan.create')->name('pelanggan.create');
    Volt::route('pelanggan/{pelanggan}', 'pages.pelanggan.detail')->name('pelanggan.detail');
    Volt::route('pelanggan/{pelanggan}/edit', 'pages.pelanggan.edit')->name('pelanggan.edit');

    // Produk Routes
    Volt::route('produk', 'pages.produk.index')->name('produk.index');
    Volt::route('produk/create', 'pages.produk.create')->name('produk.create');
    Volt::route('produk/{produk}/edit', 'pages.produk.edit')->name('produk.edit');

    // Transaksi Routes
    Volt::route('transaksi', 'pages.transaksi.index')->name('transaksi.index');
    Volt::route('transaksi/create', 'pages.transaksi.create')->name('transaksi.create');
    Volt::route('transaksi/{transaksi}/edit', 'pages.transaksi.edit')->name('transaksi.edit');
    Volt::route('transaksi/{transaksi}', 'pages.transaksi.show')->name('transaksi.show');

    // Pengiriman Routes
    Volt::route('pengiriman', 'pages.pengiriman.index')->name('pengiriman.index');
    Volt::route('pengiriman/create', 'pages.pengiriman.create')->name('pengiriman.create');
    Volt::route('pengiriman/{pengiriman}/edit', 'pages.pengiriman.edit')->name('pengiriman.edit');

    // Pembayaran Routes
    Volt::route('pembayaran', 'pages.pembayaran.index')->name('pembayaran.index');
    Volt::route('pembayaran/create', 'pages.pembayaran.create')->name('pembayaran.create');
    Volt::route('pembayaran/{pembayaran}/edit', 'pages.pembayaran.edit')->name('pembayaran.edit');

    // Custom Request Routes
    Volt::route('custom-request', 'pages.custom-request.index')->name('custom-request.index');
});

require __DIR__ . '/auth.php';
