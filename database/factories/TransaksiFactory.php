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
        return [
            'user_id' => \App\Models\User::factory(),
            'produk_id' => \App\Models\Produk::factory(),
            'jumlah' => fake()->numberBetween(1, 5),
            'total_harga' => function (array $attributes) {
                $produk = \App\Models\Produk::find($attributes['produk_id']);
                return $produk ? $produk->harga * $attributes['jumlah'] : fake()->numberBetween(100000, 10000000);
            },
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'tanggal_transaksi' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
