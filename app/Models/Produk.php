<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'kategori',
        'status'
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
