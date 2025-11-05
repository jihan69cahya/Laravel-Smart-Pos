<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 't_penjualan';
    protected $guarded = [];

    function relPelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
    function relKasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id');
    }

    function relDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id');
    }
}
