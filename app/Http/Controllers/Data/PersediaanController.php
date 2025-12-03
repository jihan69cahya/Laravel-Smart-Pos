<?php

namespace App\Http\Controllers\Data;

use App\Models\Stok;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PersediaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Stok::with('relProduk.relSatuan')->get();
            return DataTables::of($data)
                ->addColumn("kode_produk", function ($row) {
                    return $row->relProduk->kode;
                })
                ->addColumn("produk", function ($row) {
                    return $row->relProduk->nama;
                })
                ->addColumn("satuan", function ($row) {
                    return $row->relProduk->relSatuan->nama;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Persediaan', 'url' => null]
        ];
        return view('data.persediaan.index', compact('breadcrumb'));
    }
}
