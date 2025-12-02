<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\PembelianDetail;
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

    public function getProdukPembelian(Request $request)
    {
        $ids = $request->ids;
        $id_produk_edit = $request->id_produk_edit;

        $produk = Produk::with('relSatuan')
            ->when($ids, function ($query, $ids) {
                $query->whereNotIn('id', $ids);
            })
            ->when($id_produk_edit, function ($query, $id_produk_edit) {
                $query->orWhere('id', $id_produk_edit);
            })
            ->get();
        return response()->json($produk);
    }

    public function getProdukPembelianKode(Request $request)
    {
        $kode = $request->searching;
        $produk = Produk::with('relSatuan')->where('kode', $kode)->first();
        return response()->json($produk);
    }

    public function getDetailPembelian(Request $request)
    {
        $id_pembelian = decrypt($request->id_pembelian);

        $detail = PembelianDetail::with('relProduk.relSatuan')
            ->where('id_pembelian', $id_pembelian)
            ->get();
        return response()->json($detail);
    }
}
