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

    protected $with = ['pelanggan.user'];

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
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
}
