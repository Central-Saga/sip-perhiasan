<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create regular users
        User::factory(10)->create([
            'role' => 'user'
        ]);

        // Run other seeders in specific order
        $this->call([
            ProdukSeeder::class,          // First seed products
            PelangganSeeder::class,       // Then seed customers
            TransaksiSeeder::class,       // Then create transactions
            PembayaranSeeder::class,      // Then create payments
            PengirimanSeeder::class,      // Then create shipments
            CustomRequestSeeder::class,    // Finally custom requests
        ]);
    }
}
