<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomRequestFactory extends Factory
{
    public function definition(): array
    {
        $jenis = fake()->randomElement(['Cincin', 'Kalung', 'Gelang', 'Anting']);
        
        $materials = ['Emas', 'Perak', 'Platinum', 'Titanium', 'Stainless Steel'];
        $sizes = ['5', '6', '7', '8', '9', '10', 'S', 'M', 'L'];
        
        return [
            'pelanggan_id' => Pelanggan::factory(), // Create a new Pelanggan instance
            'deskripsi' => fake()->sentences(3, true),
            'estimasi_harga' => fake()->numberBetween(1000000, 10000000),
            'kategori' => $jenis,
            'material' => fake()->randomElement($materials),
            'ukuran' => fake()->randomElement($sizes),
            'gambar_referensi' => fake()->optional(0.7)->imageUrl(640, 480, 'jewelry', true),
            'berat' => fake()->randomFloat(2, 1, 100),
            'transaksi_id' => null // Will be set when a transaction is created
        ];
    }
}
