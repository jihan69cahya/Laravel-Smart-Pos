<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function getParentMenu()
    {
        $data = Menu::whereNull('id_parent')->get();
        return response()->json($data);
    }
}
