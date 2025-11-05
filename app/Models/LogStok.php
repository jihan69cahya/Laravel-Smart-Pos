<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogStok extends Model
{
    use HasFactory;
    protected $table = 't_log_stok';
    protected $guarded = [];

    function relProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}
