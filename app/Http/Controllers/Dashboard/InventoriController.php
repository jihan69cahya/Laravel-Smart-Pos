<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoriController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['title' => 'Dashboard', 'url' => route('dashboard.inventori')],
            ['title' => 'Admin Inventori', 'url' => null]
        ];
        return view('dashboard.inventori', compact('breadcrumb'));
    }
}
