<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    protected $table = 't_stok';
    protected $guarded = [];

    function relProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}
