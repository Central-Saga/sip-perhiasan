<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;
    
    protected $table = 'pengirimans';

    protected $fillable = [
        'transaksi_id',
        'status',
        'deskripsi',
        'tanggal_pengiriman'
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'datetime'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
