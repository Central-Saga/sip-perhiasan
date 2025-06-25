<?php

namespace Database\Seeders;

use App\Models\CustomRequest;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class CustomRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Get all pelanggan
        $pelanggans = Pelanggan::all();
        
        if ($pelanggans->isEmpty()) {
            // If no pelanggan exists, create some first
            $pelanggans = Pelanggan::factory(5)->create();
        }
        
        // Create custom requests for each pelanggan
        foreach ($pelanggans as $pelanggan) {
            CustomRequest::factory()
                ->count(rand(1, 3))
                ->create([
                    'pelanggan_id' => $pelanggan->id
                ]);
        }
    }
}
