<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'deskripsi' => fake()->paragraph(),
            'estimasi_harga' => fake()->numberBetween(1000000, 10000000),
            'kategori' => fake()->randomElement(['Cincin', 'Kalung', 'Gelang', 'Anting']),
            'lokasi' => fake()->city(),
        ];
    }
}
