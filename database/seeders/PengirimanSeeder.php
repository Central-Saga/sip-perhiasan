<?php

namespace Database\Seeders;

use App\Models\Pengiriman;
use Illuminate\Database\Seeder;

class PengirimanSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample shipments
        Pengiriman::factory()
            ->count(30)
            ->create();
    }
}
