<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; // Explicitly set the table name

    protected $fillable = [
        'transaksi_id',
        'metode',
        'total_bayar',
        'bukti_transfer',
        'status'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
