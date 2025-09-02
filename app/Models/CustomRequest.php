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
        'material',
        'ukuran',
        'gambar_referensi',
        'berat',
        'detail_transaksi_id',
        'status'

    ];

    protected $casts = [
        'estimasi_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Allowed status values aligned with migration enum
    public const STATUSES = [
        'pending',
        'reviewed',
        'price_proposed',
        'approved',
        'rejected',
        'in_progress',
        'completed',
        'cancelled',
    ];

    /**
     * Get the pelanggan that owns the custom request
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Get the detail transaksi associated with the custom request
     */
    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
    }
}
