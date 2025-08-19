<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/custom', function () {
    return view('Public.custom');
})->name('custom');

Route::post('/custom', function (Illuminate\Http\Request $request) {
    // Validate form
    $request->validate([
        'deskripsi' => 'required|min:10',
        'material' => 'required',
        'ukuran' => 'required',
        'kategori' => 'required',
        'gambar_referensi' => 'nullable|image|max:2048',
    ]);

    // Handle file upload
    $gambarPath = null;
    if ($request->hasFile('gambar_referensi')) {
        $gambarPath = $request->file('gambar_referensi')->store('custom-requests', 'public');
    }
    
    // Check if user is logged in
    if (Auth::check()) {
        // Create custom request in database
        $customRequest = \App\Models\CustomRequest::create([
            'pelanggan_id' => Auth::user()->pelanggan->id,
            'deskripsi' => $request->deskripsi,
            'material' => $request->material,
            'ukuran' => $request->ukuran,
            'kategori' => $request->kategori,
            'gambar_referensi' => $gambarPath,
            'estimasi_harga' => 0, // Will be set by admin later
            'berat' => 0, // Will be set by admin later
        ]);
        
        return redirect()->route('custom')->with('message', 'Custom request berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    } else {
        // Store in session for guest users
        $request->session()->put('custom_request', [
            'deskripsi' => $request->deskripsi,
            'material' => $request->material,
            'ukuran' => $request->ukuran,
            'kategori' => $request->kategori,
            'gambar_referensi' => $gambarPath,
        ]);
        
        return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk melanjutkan custom request.');
    }
})->name('custom.submit');

Route::get('/cart', function () {
    // Dummy transaksi, ganti dengan query ke database jika sudah dinamis
    $transaksis = [];
    return view('Public.cart', compact('transaksis'));
})->name('cart');



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

Route::get('/katalog', function () {
    return view('Public.katalog');
})->name('katalog');

require __DIR__.'/auth.php';
