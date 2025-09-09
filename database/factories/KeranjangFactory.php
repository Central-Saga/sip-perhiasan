<?php

namespace Database\Factories;

use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\CustomRequest;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeranjangFactory extends Factory
{
    protected $model = Keranjang::class;

    public function definition(): array
    {
        $pelanggan = Pelanggan::factory()->create();

        // Decide: product or custom request
        $useProduct = fake()->boolean(70);
        $produkId = null;
        $customId = null;
        $harga = 0;

        if ($useProduct) {
            $produk = Produk::factory()->create();
            $produkId = $produk->id;
            $harga = (int) $produk->harga;
        } else {
            $cr = CustomRequest::factory()->create();
            $customId = $cr->id;
            $harga = (int) $cr->estimasi_harga;
        }

        $jumlah = fake()->numberBetween(1, 3);
        $hargaSatuan = $harga;
        $subtotal = $jumlah * $hargaSatuan;

        return [
            'pelanggan_id' => $pelanggan->id,
            'produk_id' => $produkId,
            'custom_request_id' => $customId,
            'jumlah' => $jumlah,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
        ];
    }
}
