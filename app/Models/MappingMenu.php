<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MappingMenu extends Model
{
    use HasFactory;

    protected $table = 't_mapping_menu';
    protected $guarded = [];

    function relMenu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }
    function relRole()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }
}
