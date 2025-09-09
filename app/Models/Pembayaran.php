<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'transaksi_id',
        'metode',
        'bukti_transfer',
        'status',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime'
    ];

    protected $with = ['transaksi']; // Eager load transaksi by default

    const STATUS_PENDING = 'PENDING';
    const STATUS_DIBAYAR = 'DIBAYAR';
    const STATUS_SELESAI = 'SELESAI';
    const STATUS_DITOLAK = 'DITOLAK';

    const METODE_CASH = 'cash';
    const METODE_TRANSFER = 'transfer';

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
