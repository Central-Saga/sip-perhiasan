<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\CustomRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomRequestFactory extends Factory
{
    public function definition(): array
    {
        $jenis = fake()->randomElement(['Cincin', 'Kalung', 'Gelang', 'Anting']);

        $materials = ['Emas', 'Perak', 'Platinum', 'Titanium', 'Stainless Steel'];
        $sizes = ['5', '6', '7', '8', '9', '10', 'S', 'M', 'L'];

        return [
            'pelanggan_id' => Pelanggan::factory(),
            'deskripsi' => fake()->sentences(3, true),
            'estimasi_harga' => fake()->randomFloat(2, 250000, 15000000),
            'kategori' => $jenis,
            'material' => fake()->randomElement($materials),
            'ukuran' => fake()->randomElement($sizes),
            'gambar_referensi' => fake()->optional(0.7)->imageUrl(640, 480, 'jewelry', true),
            'berat' => fake()->randomFloat(2, 1, 100),
            'detail_transaksi_id' => null,
            'status' => fake()->randomElement(CustomRequest::STATUSES),
        ];
    }
}

