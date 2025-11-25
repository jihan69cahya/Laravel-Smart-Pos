<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Produk;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function getParentMenu()
    {
        $data = Menu::whereNull('id_parent')->get();
        return response()->json($data);
    }

    public function getProdukSaldoAwal(Request $request)
    {
        $id_produk = $request->id;
        $produk = Produk::with('relSatuan')->whereDoesntHave('relSaldoAwal')
            ->when($id_produk, function ($query, $id_produk) {
                $query->orWhere('id', $id_produk);
            })->get();
        return response()->json($produk);
    }
}
