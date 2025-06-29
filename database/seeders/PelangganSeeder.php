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
        // Create customers
        \App\Models\User::factory()
            ->count(10)
            ->create([
                'role' => 'user' // Using 'user' instead of 'customer' to match DatabaseSeeder's role
            ]);
    }
}
