<?php

namespace Database\Seeders;

use App\Models\Keranjang;
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
        // Run other seeders in specific order
        $this->call([
            UserSeeder::class,            // Ensure users exist (idempotent)
            RolePermissionSeeder::class, // Create roles/permissions and assign roles
            PelangganSeeder::class,    // Then create pelanggans
            ProdukSeeder::class,       // Then create products
            // CustomRequestSeeder::class, // Then create custom requests from pelanggans
            // KeranjangSeeder::class,    // Seed carts after products and custom requests
            // TransaksiSeeder::class,    // Finally create transactions (will also create pembayaran and pengiriman)
            // DetailTransaksiSeeder::class, // Tambah detail transaksi setelah transaksi
            // PembayaranSeeder::class,      // Then create payments
            // PengirimanSeeder::class,      // Then create shipments
            // KeranjangSeeder::class,       // Finally create carts
        ]);
    }
}
