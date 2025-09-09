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
        // Get all pelanggans
        $pelanggans = \App\Models\Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Create 1-3 transactions per pelanggan
            \App\Models\Transaksi::factory()
                ->count(rand(1, 3))
                ->create([
                    'user_id' => $pelanggan->user_id,
                    'pelanggan_id' => $pelanggan->id
                ])
                ->each(function ($transaksi) {
                    // Create related payment
                    \App\Models\Pembayaran::factory()->create([
                        'transaksi_id' => $transaksi->id,
                        'status' => $transaksi->status === 'SELESAI' ? 'SELESAI' : 
                                  ($transaksi->status === 'DIBATALKAN' ? 'DITOLAK' : 
                                  ($transaksi->status === 'DIPROSES' ? 'DIBAYAR' : 'PENDING'))
                    ]);

                    // Create related shipment if payment is completed
                    if (in_array($transaksi->status, ['DIPROSES', 'SELESAI'])) {
                        \App\Models\Pengiriman::factory()->create([
                            'transaksi_id' => $transaksi->id
                        ]);
                    }
                });
        }
    }
}

