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

        // Create role for Owner (owner can manage users and generally has admin-level access)
        $owner = Role::firstOrCreate(['name' => 'Owner']);
        $owner->syncPermissions([
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

        // Create role for Pelanggan
        $pelanggan = Role::firstOrCreate(['name' => 'Pelanggan']);
        $pelanggan->syncPermissions([
            'melihat dashboard',
            'melihat produk',
            'melakukan transaksi',
            'mengajukan custom request',
        ]);

        // Assign roles to specific users if they exist
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['Admin']);
        }

        $ownerUser = User::where('email', 'owner@example.com')->first();
        if ($ownerUser) {
            $ownerUser->syncRoles(['Owner']);
        }

        // Assign Pelanggan role to other users (exclude admin and owner)
        $otherUsers = User::whereNotIn('email', ['admin@example.com', 'owner@example.com'])->get();
        foreach ($otherUsers as $user) {
            // Keep existing special roles if any, otherwise set Pelanggan
            if ($user->roles()->count() === 0) {
                $user->assignRole('Pelanggan');
            }
        }
    }
}
