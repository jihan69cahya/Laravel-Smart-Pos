<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 't_pembelian';
    protected $guarded = [];

    function relSupplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    function relDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian', 'id');
    }
}
