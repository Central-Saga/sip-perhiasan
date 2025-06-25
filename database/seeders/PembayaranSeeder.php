<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Get all transaksi that don't have payments
        $transaksis = Transaksi::doesntHave('pembayaran')->get();
        
        foreach ($transaksis as $transaksi) {
            Pembayaran::factory()
                ->create([
                    'transaksi_id' => $transaksi->id
                ]);
        }

        // Create some additional random payments for testing
        Pembayaran::factory()
            ->count(10)
            ->create();
    }
}
