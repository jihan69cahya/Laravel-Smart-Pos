<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodeBayar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_metode_bayar';
    protected $guarded = [];
}
