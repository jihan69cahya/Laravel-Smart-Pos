<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'm_menu';
    protected $guarded = [];

    function relMapping()
    {
        return $this->hasMany(MappingMenu::class, 'id_menu', 'id');
    }

    function relParent()
    {
        return $this->belongsTo(Menu::class, 'id_parent', 'id');
    }
}
