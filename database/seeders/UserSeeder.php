<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelanggan;
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

        // Ensure a single Owner user exists (idempotent)
        User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                // users.role enum supports 'admin' or 'user'; keep 'user' here
                'role' => 'user',
            ]
        );

        // Ensure a single Pelanggan user exists (idempotent)
        $pelangganUser = User::updateOrCreate(
            ['email' => 'pelanggan@example.com'],
            [
                'name' => 'Pelanggan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                // enum only allows 'admin' | 'user'
                'role' => 'user',
            ]
        );

        // Create pelanggan data directly (simple approach)
        if (!$pelangganUser->pelanggan) {
            \App\Models\Pelanggan::create([
                'user_id' => $pelangganUser->id,
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Contoh No. 123, Jakarta',
                'status' => 'Aktif',
            ]);
        }


        // Ensure there are at least 10 regular users
        $currentRegulars = User::whereNotIn('email', ['admin@example.com', 'owner@example.com'])->count();
        $toCreate = max(0, 10 - $currentRegulars);
        if ($toCreate > 0) {
            User::factory($toCreate)->create([
                'role' => 'user',
            ]);
        }
    }
}
