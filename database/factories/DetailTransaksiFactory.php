<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\DetailTransaksi>
 */
class DetailTransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produk = Produk::inRandomOrder()->first() ?? Produk::factory()->create();
        $jumlah = $this->faker->numberBetween(1, 5);
        $sub_total = $produk->harga * $jumlah;
        return [
            'transaksi_id' => Transaksi::inRandomOrder()->first()->id ?? Transaksi::factory()->create()->id,
            'produk_id' => $produk->id,
            'jumlah' => $jumlah,
            'sub_total' => $sub_total,
        ];
    }
}
