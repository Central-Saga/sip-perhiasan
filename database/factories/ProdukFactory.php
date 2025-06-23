<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_produk' => fake()->words(2, true),
            'deskripsi' => fake()->paragraph(),
            'harga' => fake()->numberBetween(100000, 10000000),
            'stok' => fake()->numberBetween(1, 100),
            'foto' => fake()->imageUrl(),
            'kategori' => fake()->randomElement(['Cincin', 'Kalung', 'Gelang', 'Anting']),
            'status' => fake()->boolean(),
        ];
    }
}
