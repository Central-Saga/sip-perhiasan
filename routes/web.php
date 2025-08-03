<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', function () {
    $produkList = [
        [
            'id' => 1,
            'nama_produk' => 'Cincin Emas Klasik',
            'harga' => 2750000,
            'stok' => 8,
            'foto' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Cincin',
        ],
        [
            'id' => 2,
            'nama_produk' => 'Kalung Silver Bliss',
            'harga' => 3200000,
            'stok' => 5,
            'foto' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Kalung',
        ],
        [
            'id' => 3,
            'nama_produk' => 'Gelang Berlian Mewah',
            'harga' => 4500000,
            'stok' => 3,
            'foto' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Gelang',
        ],
    ];
    
    return view('welcome', compact('produkList'));
})->name('home');

// Landing page custom menu (produk landing statis)
Route::get('/produk-landing', function () {
    $produkList = [
        [
            'id' => 1,
            'nama_produk' => 'Cincin Emas Klasik',
            'harga' => 2750000,
            'stok' => 8,
            'foto' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Cincin',
        ],
        [
            'id' => 2,
            'nama_produk' => 'Kalung Silver Bliss',
            'harga' => 3200000,
            'stok' => 5,
            'foto' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Kalung',
        ],
        [
            'id' => 3,
            'nama_produk' => 'Gelang Berlian Mewah',
            'harga' => 4500000,
            'stok' => 3,
            'foto' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Gelang',
        ],
        [
            'id' => 4,
            'nama_produk' => 'Anting Silver Premium',
            'harga' => 1200000,
            'stok' => 10,
            'foto' => 'https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Anting',
        ],
        [
            'id' => 5,
            'nama_produk' => 'Liontin Gold Elegant',
            'harga' => 2900000,
            'stok' => 4,
            'foto' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Liontin',
        ],
    ];
    
    return view('Public.produk', compact('produkList'));
})->name('produk.landing');

Route::get('/about', function () {
    return view('Public.about');
})->name('about');


Route::get('/cart', function () {
    // Dummy transaksi, ganti dengan query ke database jika sudah dinamis
    $transaksis = [];
    return view('Public.cart', compact('transaksis'));
})->name('cart');

// Transaction detail page
Route::get('/transaksi/{id}', function ($id) {
    // For a real app, you would fetch this data from the database
    // This is just dummy data for demonstration
    $transaksi = new \stdClass();
    $transaksi->id = $id;
    $transaksi->kode_transaksi = 'TRX-'.str_pad($id, 5, '0', STR_PAD_LEFT);
    $transaksi->tanggal_transaksi = now();
    $transaksi->total_harga = 1500000;
    $transaksi->status = 'Selesai';
    
    // Create dummy details as a collection of objects
    $details = collect();
    $detail = new \stdClass();
    $detail->produk = new \stdClass();
    $detail->produk->nama_produk = 'Cincin Emas Klasik';
    $detail->jumlah = 1;
    $detail->sub_total = 1500000;
    $details->push($detail);
    
    // Set the detailTransaksi as a dynamic property
    $transaksi->detailTransaksi = $details;
    
    // Create dummy custom request
    $transaksi->customRequest = null; // Set to null or create a dummy object if needed
    
    // Create dummy pengiriman
    $transaksi->pengiriman = null; // Set to null or create a dummy object if needed
    
    return view('Public.transaksi_detail', compact('transaksi'));
})->name('transaksi.show');

// Checkout page
Route::get('/checkout', function () {
    return view('Public.checkout');
})->name('checkout');

// Checkout submit (dummy, redirect to home)
Route::post('/checkout', function () {
    // Proses custom request dan pembayaran di sini
    // ...
    // Kosongkan cart localStorage via JS setelah submit
    return redirect('/')->with('success', 'Pembayaran & custom request berhasil diproses!');
})->name('checkout.submit');

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

Route::get('/katalog', function () {
    return view('Public.katalog');
})->name('katalog');

require __DIR__.'/auth.php';
