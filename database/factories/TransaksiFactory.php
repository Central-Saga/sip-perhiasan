<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pelanggan = \App\Models\Pelanggan::inRandomOrder()->first() ?? \App\Models\Pelanggan::factory()->create();
        
        return [
            'user_id' => $pelanggan->user_id,
            'pelanggan_id' => $pelanggan->id,
            'kode_transaksi' => 'TRX-' . strtoupper(fake()->unique()->bothify('##??##')),
            'total_harga' => fake()->numberBetween(1000000, 25000000),
            'status' => fake()->randomElement(['PENDING', 'DIPROSES', 'SELESAI', 'DIBATALKAN']),
            'tipe_pesanan' => fake()->randomElement(['READY', 'CUSTOM']),
            'tanggal_transaksi' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
