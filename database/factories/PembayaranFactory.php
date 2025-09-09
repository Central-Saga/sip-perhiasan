<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    public function definition(): array
    {
        $metode = fake()->randomElement([Pembayaran::METODE_CASH, Pembayaran::METODE_TRANSFER]);
        $status = fake()->randomElement([
            Pembayaran::STATUS_PENDING,
            Pembayaran::STATUS_DIBAYAR,
            Pembayaran::STATUS_SELESAI,
            Pembayaran::STATUS_DITOLAK
        ]);

        return [
            'transaksi_id' => Transaksi::factory(),
            'metode' => $metode,
            'status' => $status,
            'tanggal_bayar' => $status !== Pembayaran::STATUS_PENDING ? fake()->dateTimeBetween('-1 month') : null,
            'bukti_transfer' => $metode === Pembayaran::METODE_TRANSFER ? 'bukti_transfer/bukti-' . fake()->uuid() . '.jpg' : null,
        ];
    }
}
