<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRequest extends Model
{
    use HasFactory;
   protected $table = 'custom_request'; // Explicitly set the table name
    protected $fillable = [
        'pelanggan_id',
        'deskripsi',
        'estimasi_harga',
        'kategori',
        'berat',
        'transaksi_id'
    ];

    protected $casts = [
        'estimasi_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the pelanggan that owns the custom request
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Get the transaksi associated with the custom request
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
