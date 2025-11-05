<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;
    protected $table = 't_pembelian_detail';
    protected $guarded = [];

    function relProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
    function relPembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id');
    }
    function relLogStok()
    {
        return $this->belongsTo(LogStok::class, 'id_log_stok', 'id');
    }
}
