<?php

namespace Database\Factories;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'metode' => fake()->randomElement(['transfer', 'cash']),
            'total_bayar' => fake()->numberBetween(100000, 10000000),
            'status' => fake()->randomElement(['pending', 'confirmed', 'failed']),
            'bukti_transfer' => fake()->randomElement([null, 'bukti.jpg']),
        ];
    }
}
