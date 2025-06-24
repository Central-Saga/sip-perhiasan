<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
});

require __DIR__.'/auth.php';
