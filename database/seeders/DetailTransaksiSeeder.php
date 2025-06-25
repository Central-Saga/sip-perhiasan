<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Produk;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = Transaksi::all();
        $produkIds = Produk::pluck('id')->toArray();
        foreach ($transaksis as $transaksi) {
            // Setiap transaksi punya 1-3 detail
            $jumlahDetail = rand(1, 3);
            for ($i = 0; $i < $jumlahDetail; $i++) {
                $produkId = $produkIds[array_rand($produkIds)];
                $jumlah = rand(1, 5);
                $harga = Produk::find($produkId)->harga;
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'sub_total' => $jumlah * $harga,
                ]);
            }
        }
    }
}
