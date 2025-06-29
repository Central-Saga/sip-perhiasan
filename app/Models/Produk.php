<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukFactory> */
    use HasFactory;

    protected $table = 'produk'; // Explicitly set the table name to match migration

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'kategori',
        'status'
    ];
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, foreignKey: 'detail_transaksi_id');
    }

}
