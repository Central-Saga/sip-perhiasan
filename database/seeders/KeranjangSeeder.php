<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\CustomRequest;
use App\Models\User;
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
        if (User::count() === 0) {
            User::factory()->count(5)->pelanggan()->create();
        }

        // For each user, create 1-3 cart items
        User::all()->each(function ($user) {
            Keranjang::factory()->count(fake()->numberBetween(1, 3))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}

