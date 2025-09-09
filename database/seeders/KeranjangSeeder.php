<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\CustomRequest;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class KeranjangSeeder extends Seeder
{
    public function run(): void
    {
        if (Produk::count() === 0) {
            Produk::factory()->count(10)->create();
        }
        if (CustomRequest::count() === 0) {
            CustomRequest::factory()->count(5)->create();
        }
        if (Pelanggan::count() === 0) {
            Pelanggan::factory()->count(5)->create();
        }

        // For each pelanggan, create 1-3 cart items
        Pelanggan::all()->each(function ($pelanggan) {
            Keranjang::factory()->count(fake()->numberBetween(1, 3))->create([
                'pelanggan_id' => $pelanggan->id,
            ]);
        });
    }
}
