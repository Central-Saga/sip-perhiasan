<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganFactory> */
    use HasFactory;

    protected $table = 'pelanggan'; // Explicitly set the table name

    protected $fillable = [
        'user_id',
        'no_telepon',
        'alamat',
        'status'
    ];

    protected $hidden = [
        'password'
    ];

    // Accessor untuk status
    public function getStatusAttribute($value)
    {
        return $value;
    }

    // Mutator untuk status
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
    }

    // Helper method untuk cek status aktif
    public function isActive()
    {
        return $this->status === 'Aktif';
    }

    // Helper method untuk cek status tidak aktif
    public function isInactive()
    {
        return $this->status === 'Tidak Aktif';
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customRequests()
    {
        return $this->hasMany(CustomRequest::class);
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }
}
