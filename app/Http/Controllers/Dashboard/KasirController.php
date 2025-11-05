<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['title' => 'Dashboard', 'url' => route('dashboard.kasir')],
            ['title' => 'Kasir', 'url' => null]
        ];
        return view('dashboard.kasir', compact('breadcrumb'));
    }
}
