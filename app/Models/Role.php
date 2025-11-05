<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'm_role';
    protected $guarded = [];

    function relMapping()
    {
        return $this->hasMany(MappingMenu::class, 'id_role', 'id');
    }
}
