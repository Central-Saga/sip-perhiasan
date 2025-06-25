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
}
