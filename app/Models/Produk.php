<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_produk';
    protected $guarded = [];

    function relKategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }

    function relSatuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id');
    }
    public function hargaTerbaru()
    {
        return $this->hasOne(ProdukHarga::class, 'id_produk', 'id')->latest('tanggal');
    }
}
