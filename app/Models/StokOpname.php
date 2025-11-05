<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;
    protected $table = 't_stok_opname';
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
