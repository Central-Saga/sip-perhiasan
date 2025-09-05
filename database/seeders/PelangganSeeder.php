<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create customers with pelanggan role and data
        \App\Models\User::factory()
            ->count(10)
            ->pelanggan()
            ->create([
                'role' => 'user' // Using 'user' instead of 'customer' to match DatabaseSeeder's role
            ]);
    }
}
