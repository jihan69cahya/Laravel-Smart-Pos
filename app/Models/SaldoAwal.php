<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    use HasFactory;
    protected $table = 't_saldo_awal';
    protected $guarded = [];

    function relProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }

    function relLogStok()
    {
        return $this->belongsTo(LogStok::class, 'id_log_stok', 'id');
    }
}
