@extends('layouts.app')
@section('title', 'Tentang Bliss Silversmith')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 text-center bg-white dark:bg-slate-900 dark:text-slate-100 rounded-xl shadow-lg">
<h1 class="text-xl md:text-2xl font-bold text-slate-700 dark:text-slate-100 mb-6 flex items-center justify-center gap-2"><i class="fa-solid fa-circle-info text-indigo-500 dark:text-indigo-300"></i> Tentang Bliss Silversmith</h1>
    <p class="text-lg text-slate-600 dark:text-slate-300 mb-8">UMKM Bliss Silversmith adalah pengrajin perhiasan lokal yang mengutamakan kualitas, keaslian, dan desain elegan. Kami hadir untuk memenuhi kebutuhan perhiasan Anda dengan pelayanan ramah dan proses transaksi yang mudah.</p>
    <div class="flex flex-col md:flex-row justify-center gap-8 mt-8">
        <div class="flex-1 bg-white dark:bg-slate-800 rounded-xl shadow p-6 transition-colors">
            <h3 class="text-base font-semibold text-indigo-700 dark:text-indigo-300 mb-2 flex items-center gap-2"><i class="fa-solid fa-medal"></i> Kualitas Premium</h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm">Setiap produk dibuat dengan bahan pilihan dan detail terbaik.</p>
        </div>
        <div class="flex-1 bg-white dark:bg-slate-800 rounded-xl shadow p-6 transition-colors">
            <h3 class="text-base font-semibold text-indigo-700 dark:text-indigo-300 mb-2 flex items-center gap-2"><i class="fa-solid fa-shield-halved"></i> Transaksi Aman</h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm">Sistem pembayaran dan pengiriman yang terpercaya dan transparan.</p>
        </div>
        <div class="flex-1 bg-white dark:bg-slate-800 rounded-xl shadow p-6 transition-colors">
            <h3 class="text-base font-semibold text-indigo-700 dark:text-indigo-300 mb-2 flex items-center gap-2"><i class="fa-solid fa-crown"></i> Desain Eksklusif</h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm">Koleksi desain unik, elegan, dan selalu mengikuti tren.</p>
        </div>
    </div>
    <div class="mt-12">
        <h2 class="text-lg font-semibold mb-4 flex items-center justify-center gap-2"><i class="fa-solid fa-truck-fast text-indigo-400 dark:text-indigo-300"></i> Pengiriman</h2>
        <p class="text-slate-600 dark:text-slate-300 text-sm">Kami bekerja sama dengan jasa pengiriman terpercaya untuk memastikan produk sampai ke tangan Anda dengan aman dan tepat waktu. Status pengiriman dapat dipantau pada menu transaksi.</p>
    </div>
    <div class="mt-12">
        <h2 class="text-lg font-semibold mb-4 flex items-center justify-center gap-2"><i class="fa-solid fa-gem text-indigo-400 dark:text-indigo-300"></i> Custom Request</h2>
        <p class="text-slate-600 dark:text-slate-300 text-sm">Ingin perhiasan dengan desain khusus? Ajukan custom request melalui menu transaksi, dan tim kami akan membantu mewujudkan perhiasan impian Anda.</p>
    </div>
</div>
@endsection
