<?php

namespace Database\Factories;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengirimanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'deskripsi' => fake()->sentence(),
            'tanggal_pengiriman' => fake()->dateTimeBetween('-1 week', '+1 week'),
        ];
    }
}
