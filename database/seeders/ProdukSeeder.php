<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Cincin', 'Kalung', 'Gelang', 'Anting'];
        
        foreach ($categories as $category) {
            \App\Models\Produk::factory()
                ->count(5) // 5 products per category
                ->create([
                    'kategori' => $category
                ]);
        }
    }
}
