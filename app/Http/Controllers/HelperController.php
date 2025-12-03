<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Produk;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function getProdukStokOpname(Request $request)
    {
        $id_produk = $request->id;
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $produk_opname = StokOpname::where('tanggal', $tanggal)
            ->pluck('id_produk')
            ->toArray();

        $excluded = $produk_opname;

        if ($id_produk) {
            $excluded = array_diff($produk_opname, [$id_produk]);
        }
        $produk = DB::table('m_produk as p')
            ->leftJoin('t_log_stok as ls', 'ls.id_produk', '=', 'p.id') // JOIN biasa tanpa whereDate
            ->leftJoin('m_satuan as s', 's.id', '=', 'p.id_satuan')
            ->select(
                'p.*',
                's.nama as satuan',
                DB::raw('COALESCE(SUM(CASE WHEN ls.tanggal <= "' . $tanggal . '" THEN ls.unit_masuk ELSE 0 END),0) - 
                 COALESCE(SUM(CASE WHEN ls.tanggal <= "' . $tanggal . '" THEN ls.unit_keluar ELSE 0 END),0) AS stok')
            )
            ->whereNotIn('p.id', $excluded)
            ->groupBy('p.id', 's.nama')
            ->get();

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
