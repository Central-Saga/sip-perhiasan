<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;




Route::prefix('/')->group(function () {
    Volt::route('/', 'landing.home')->name('home');
    Volt::route('about', 'landing.about')->name('about');
    Volt::route('custom', 'landing.custom_request')->name('custom');
    Volt::route('custom/submit', 'landing.custom_request')->name('custom.submit');
    Volt::route('cart', 'landing.cart')->name('cart');
    Volt::route('checkout', 'landing.checkout')->name('checkout');
    Volt::route('checkout/submit', 'landing.checkout')->name('checkout.submit');
    Volt::route('produk', 'landing.produk')->name('produk');
    Volt::route('produk/{id}', 'landing.produk_detail')->name('produk.detail');
    Volt::route('transaksi', 'landing.transaksi')->name('transaksi');
    Volt::route('transaksi/{id}', 'landing.transaksi_detail')->name('transaksi.detail');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings Routes
    Route::redirect('settings', 'settings/profile');
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
