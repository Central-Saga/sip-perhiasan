<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample payments
        Pembayaran::factory()
            ->count(30)
            ->create();
    }
}
