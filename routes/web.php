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
    // Handle Custom Request form submission (POST)
    Route::post('custom/submit', function (Request $request) {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'material' => 'required|string|max:100',
            'ukuran' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_referensi' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar_referensi')) {
            $request->file('gambar_referensi')->store('custom-referensi', 'public');
        }

        return back()->with('message', 'Custom request berhasil dikirim. Kami akan menghubungi Anda.');
    })->name('custom.submit');
    Volt::route('cart', 'pages.landingpage.cart.index')->name('cart');
    Volt::route('checkout', 'pages.landingpage.checkout.index')->name('checkout');
    Volt::route('checkout/submit', 'pages.landingpage.checkout.index')->name('checkout.submit');
    Volt::route('transaksi', 'pages.landingpage.transaksi.index')->name('transaksi');
    Volt::route('transaksi/{id}', 'pages.landingpage.transaksi.detail')->name('transaksi.detail');
});

// Fallback ke landing bila URL tidak ditemukan
Route::fallback(function () {
    return redirect()->route('home');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Settings Routes
    Route::redirect('settings', 'admin/settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Pelanggan Routes
    Volt::route('pelanggan', 'pages.pelanggan.index')->name('pelanggan.index');
    Volt::route('pelanggan/create', 'pages.pelanggan.create')->name('pelanggan.create');
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
    Volt::route('custom-request/create', 'pages.custom-request.create')->name('custom-request.create');
    Volt::route('custom-request/{customRequest}/edit', 'pages.custom-request.edit')->name('custom-request.edit');
});

require __DIR__.'/auth.php';
