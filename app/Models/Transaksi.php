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
        'user_id',
        'pelanggan_id',
        'kode_transaksi',
        'total_harga',
        'status',
        'tipe_pesanan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $with = ['pelanggan.user'];

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customRequest()
    {
        return $this->hasOne(CustomRequest::class);
    }
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // Relasi ke produk melalui detail transaksi
    public function produk()
    {
        return $this->hasManyThrough(
            Produk::class,
            DetailTransaksi::class,
            'transaksi_id', // Foreign key di detail_transaksi
            'id',           // Foreign key di produk
            'id',           // Local key di transaksi
            'produk_id'     // Local key di detail_transaksi
        );
    }

    // Relasi ke pengiriman
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'transaksi_id');
    }
}
