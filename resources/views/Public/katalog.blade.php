@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8 text-yellow-700 dark:text-yellow-300">Katalog Produk</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach(\App\Models\Produk::orderByDesc('created_at')->paginate(12) as $produk)
            <div class="glass p-6 flex flex-col items-center border border-yellow-200 dark:border-yellow-700 hover:shadow-yellow-400/60 hover:scale-105 transition">
                <img src="{{ $produk->foto_produk ? asset('storage/'.$produk->foto_produk) : 'https://placehold.co/220x220?text=Perhiasan' }}" alt="{{ $produk->nama_produk }}" class="w-36 h-36 object-cover rounded-xl mb-4 border shadow">
                <h3 class="font-bold text-lg text-center mb-1 text-yellow-800 dark:text-yellow-200">{{ $produk->nama_produk }}</h3>
                <p class="text-yellow-700 dark:text-yellow-300 font-bold mb-2 text-lg">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <a href="{{ route('produk.index') }}" class="px-5 py-2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded-full shadow hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition">Lihat Detail</a>
            </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ \App\Models\Produk::orderByDesc('created_at')->paginate(12)->links() }}
    </div>
</div>
@endsection
