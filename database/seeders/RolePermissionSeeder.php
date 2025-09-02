<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if they don't exist
        $permissions = [
            'mengelola user',
            'mengelola role',
            'mengelola pelanggan',
            'mengelola produk',
            'mengelola transaksi',
            'mengelola pengiriman',
            'mengelola pembayaran',
            'mengelola custom request',
            'mengelola laporan',
            'melihat dashboard',
            'melihat produk',
            'melihat transaksi',
            'melihat pengiriman',
            'melihat pembayaran',
            'melihat custom request',
            'melakukan transaksi',
            'mengajukan custom request',
            'mencetak laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'mengelola user',
            'mengelola role',
            'mengelola pelanggan',
            'mengelola produk',
            'mengelola transaksi',
            'mengelola pengiriman',
            'mengelola pembayaran',
            'mengelola custom request',
            'mengelola laporan',
            'melihat dashboard',
            'mencetak laporan',
        ]);

        // Create role for Manager
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'mengelola pelanggan',
            'mengelola produk',
            'mengelola transaksi',
            'mengelola pengiriman',
            'mengelola pembayaran',
            'mengelola custom request',
            'melihat dashboard',
            'melihat produk',
            'melihat transaksi',
            'melihat pengiriman',
            'melihat pembayaran',
            'melihat custom request',
            'mencetak laporan',
        ]);

        // Create role for Staff
        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->syncPermissions([
            'mengelola pelanggan',
            'mengelola transaksi',
            'mengelola pengiriman',
            'mengelola pembayaran',
            'melihat dashboard',
            'melihat produk',
            'melihat transaksi',
            'melihat pengiriman',
            'melihat pembayaran',
            'melihat custom request',
        ]);

        // Create role for Pelanggan
        $pelanggan = Role::firstOrCreate(['name' => 'Pelanggan']);
        $pelanggan->syncPermissions([
            'melihat dashboard',
            'melihat produk',
            'melakukan transaksi',
            'mengajukan custom request',
        ]);

        // Assign roles to users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('Admin');
        }

        // Assign Pelanggan role to other users
        $otherUsers = User::where('email', '!=', 'admin@example.com')->get();
        foreach ($otherUsers as $user) {
            $user->assignRole('Pelanggan');
        }
    }
}
