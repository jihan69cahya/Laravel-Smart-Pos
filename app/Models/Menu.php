<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_menu';
    protected $guarded = [];

    function relMapping()
    {
        return $this->hasMany(MappingMenu::class, 'id_menu', 'id');
    }
}
