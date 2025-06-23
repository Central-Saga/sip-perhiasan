<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Transaksi::factory()
            ->count(30)
            ->create()
            ->each(function ($transaksi) {
                // Create related pembayaran
                \App\Models\Pembayaran::factory()->create([
                    'transaksi_id' => $transaksi->id,
                    'total_bayar' => $transaksi->total_harga
                ]);

                // Create related pengiriman
                \App\Models\Pengiriman::factory()->create([
                    'transaksi_id' => $transaksi->id
                ]);
            });
    }
}
