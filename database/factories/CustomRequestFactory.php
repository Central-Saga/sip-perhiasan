<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomRequestFactory extends Factory
{
    public function definition(): array
    {
        $jenis = fake()->randomElement(['Cincin', 'Kalung', 'Gelang', 'Anting']);
        
        return [
            'pelanggan_id' => Pelanggan::factory(), // Create a new Pelanggan instance
            'deskripsi' => fake()->sentences(3, true),
            'estimasi_harga' => fake()->numberBetween(1000000, 10000000),
            'kategori' => $jenis,
            'berat' => fake()->randomFloat(2, 1, 100),
            'transaksi_id' => null // Will be set when a transaction is created
        ];
    }
}
