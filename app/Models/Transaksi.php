<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiFactory> */
    use HasFactory;

    protected $table = 'transaksi'; // Explicitly set the table name

    protected $fillable = [
        'pelanggan_id',
        'produk_id',
        'jumlah',
        'total_harga',
        'status',
        'metode',
        'bukti_transfer',
        'tanggal_transaksi'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime'
    ];

    protected $with = ['pelanggan.user', 'produk'];

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public function customRequest()
    {
        return $this->hasOne(CustomRequest::class);     
    }
}
