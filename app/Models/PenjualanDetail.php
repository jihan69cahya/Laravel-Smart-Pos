<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;
    protected $table = 't_penjualan_detail';
    protected $guarded = [];

    function relProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
    function relPenjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id');
    }
    function relLogStok()
    {
        return $this->belongsTo(LogStok::class, 'id_log_stok', 'id');
    }
}
