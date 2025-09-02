<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a single Admin user exists (idempotent)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                // Keep the existing enum column in sync
                'role' => 'admin',
            ]
        );

        // Ensure there are at least 10 regular users
        $currentRegulars = User::where('email', '!=', 'admin@example.com')->count();
        $toCreate = max(0, 10 - $currentRegulars);
        if ($toCreate > 0) {
            User::factory($toCreate)->create([
                'role' => 'user',
            ]);
        }
    }
}

