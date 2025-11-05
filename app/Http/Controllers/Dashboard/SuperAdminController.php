<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['title' => 'Dashboard', 'url' => route('dashboard.superadmin')],
            ['title' => 'Superadmin', 'url' => null]
        ];
        return view('dashboard.superadmin', compact('breadcrumb'));
    }
}
