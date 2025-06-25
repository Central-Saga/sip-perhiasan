<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    /** @use HasFactory<\Database\Factories\DetailTransaksiFactory> */
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'sub_total',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
