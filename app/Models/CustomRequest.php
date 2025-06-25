<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_request',
        'pelanggan_id',
        'status',
        'jenis',
        'deskripsi',
        'referensi'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
