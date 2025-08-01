@php
    // Data produk statis di view, tidak perlu request dari route
    $id = request()->route('id');
    $produkList = [
        1 => [
            'id' => 1,
            'nama_produk' => 'Cincin Emas Klasik',
            'harga' => 2750000,
            'stok' => 8,
            'foto' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Cincin',
            'deskripsi' => 'Cincin emas klasik dengan desain elegan dan timeless. Cocok untuk berbagai acara formal maupun casual.'
        ],
        2 => [
            'id' => 2,
            'nama_produk' => 'Kalung Silver Bliss',
            'harga' => 3200000,
            'stok' => 5,
            'foto' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Kalung',
            'deskripsi' => 'Kalung silver premium dengan desain modern dan elegan. Perhiasan yang sempurna untuk melengkapi penampilan Anda.'
        ],
        3 => [
            'id' => 3,
            'nama_produk' => 'Gelang Berlian Mewah',
            'harga' => 4500000,
            'stok' => 3,
            'foto' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
            'kategori' => 'Gelang',
            'deskripsi' => 'Gelang berlian mewah dengan kualitas terbaik. Tambahkan sentuhan kemewahan dalam gaya Anda sehari-hari.'
        ],
    ];
    $produk = $produkList[$id] ?? $produkList[1]; // Default ke produk 1 jika id tidak valid
@endphp

@extends('layouts.app')
@section('title', $produk['nama_produk'].' - Detail Produk')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg flex flex-col md:flex-row gap-8 p-8">
        <img src="{{ $produk['foto'] }}" alt="{{ $produk['nama_produk'] }}" class="w-full md:w-80 h-64 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
        <div class="flex-1 flex flex-col gap-4">
            <span class="text-xs bg-slate-200/60 dark:bg-slate-700/60 text-indigo-600 dark:text-indigo-300 px-2 py-1 rounded-full w-fit flex items-center gap-1">
                <i class="fa-solid fa-gem"></i> {{ $produk['kategori'] }}
            </span>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $produk['nama_produk'] }}</h1>
            <p class="text-indigo-600 dark:text-indigo-300 font-semibold text-xl">Rp {{ number_format($produk['harga'],0,',','.') }}</p>
            <p class="text-slate-500 dark:text-slate-300 text-sm">Stok: {{ $produk['stok'] }}</p>
            <p class="text-slate-600 dark:text-slate-300">{{ $produk['deskripsi'] }}</p>
            <a href="{{ route('cart') }}" class="mt-4 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-full font-semibold transition flex items-center gap-2 w-fit"><i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang</a>
            <a href="{{ url()->previous() }}" class="mt-2 text-indigo-500 hover:underline flex items-center gap-1"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>
@endsection
