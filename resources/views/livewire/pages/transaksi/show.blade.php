<?php
use Livewire\Volt\Component;
use function Livewire\Volt\{ layout, title };
layout('components.layouts.admin');
title('Transaksi - Detail');
use App\Models\Transaksi;

new class extends Component {
    public $transaksi;

    public function mount(Transaksi $transaksi) {
        $this->transaksi = $transaksi->load(['detailTransaksi.produk', 'customRequest', 'pengiriman']);
    }
};
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-xl md:text-2xl font-bold text-slate-700 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-file-invoice-dollar text-indigo-500"></i> Detail Transaksi
    </h1>
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <div>
                <div class="font-semibold text-slate-700">Kode Transaksi:</div>
                <div class="font-mono text-indigo-700">{{ $transaksi->kode_transaksi }}</div>
            </div>
            <div>
                <div class="font-semibold text-slate-700">Tanggal:</div>
                <div>{{ $transaksi->created_at->format('d M Y H:i') }}</div>
            </div>
            <div>
                <div class="font-semibold text-slate-700">Status:</div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{
                    $transaksi->status }}</span>
            </div>
        </div>
        <div class="mb-4">
            <div class="font-semibold text-slate-700 mb-2">Detail Produk:</div>
            <ul class="divide-y divide-slate-100">
                @foreach($transaksi->detailTransaksi as $item)
                <li class="py-2 flex justify-between items-center">
                    <span>{{ $item->produk->nama_produk }} <span class="text-xs text-slate-400">x{{ $item->jumlah
                            }}</span></span>
                    <span class="text-indigo-700 font-medium">Rp {{ number_format($item->sub_total,0,',','.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-4">
            <div class="font-semibold text-slate-700">Total Harga:</div>
            <div class="text-base text-indigo-700 font-semibold">Rp {{ number_format($transaksi->total_harga,0,',','.')
                }}</div>
        </div>
        @if($transaksi->customRequest)
        <div class="mb-4 bg-indigo-50 border-l-4 border-indigo-300 p-4 rounded">
            <div class="font-semibold text-indigo-700 mb-1 flex items-center gap-2"><i class="fa-solid fa-gem"></i>
                Custom Request</div>
            <div class="text-slate-700">{{ $transaksi->customRequest->deskripsi }}</div>
            <div class="text-xs text-slate-500 mt-1">Estimasi Harga: <span class="text-indigo-700 font-semibold">Rp {{
                    number_format($transaksi->customRequest->estimasi_harga,0,',','.') }}</span></div>
        </div>
        @endif
        @if($transaksi->pengiriman)
        <div class="mb-4 bg-blue-50 border-l-4 border-blue-300 p-4 rounded">
            <div class="font-semibold text-blue-700 mb-1 flex items-center gap-2"><i class="fa-solid fa-truck-fast"></i>
                Pengiriman</div>
            <div class="text-slate-700">Status: {{ $transaksi->pengiriman->status }}</div>
            <div class="text-xs text-slate-500 mt-1">Tanggal Pengiriman: {{ $transaksi->pengiriman->tanggal_pengiriman ?
                $transaksi->pengiriman->tanggal_pengiriman->format('d M Y H:i') : '-' }}</div>
        </div>
        @endif
        <div class="mt-6 flex gap-4">
            <a href="{{ route('transaksi.index') }}"
                class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium flex items-center gap-2"><i
                    class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>
