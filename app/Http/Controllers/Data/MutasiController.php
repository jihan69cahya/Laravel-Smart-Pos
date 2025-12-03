<?php

namespace App\Http\Controllers\Data;

use App\Helpers\Date;
use App\Models\Produk;
use App\Helpers\Helper;
use App\Models\LogStok;
use Illuminate\Http\Request;
use App\Exports\MutasiExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id_produk = $request->id_produk;
            $start = $request->start;
            $end = $request->end;

            $data = LogStok::with('relProduk')
                ->when($id_produk != 'ALL', function ($query) use ($id_produk) {
                    $query->where('id_produk', $id_produk);
                })
                ->when($start && $end, function ($query) use ($start, $end) {
                    $query->whereBetween('tanggal', [$start, $end]);
                })
                ->orderBy('tanggal')
                ->get();

            return DataTables::of($data)
                ->editColumn("tanggal", function ($row) {
                    return Date::format($row->tanggal, 1);
                })
                ->editColumn("status", function ($row) {
                    return Helper::statusLogStok($row->status);
                })
                ->editColumn("produk", function ($row) {
                    return $row->relProduk->nama;
                })
                ->addColumn("jumlah", function ($row) {
                    return $row->unit_masuk ?? $row->unit_keluar;
                })
                ->editColumn("keterangan", function ($row) {
                    return $row->keterangan ?? '-';
                })
                ->addIndexColumn()
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Mutasi', 'url' => null]
        ];
        $data['produk'] = Produk::withTrashed()->get();
        return view('data.mutasi.index', compact('breadcrumb', 'data'));
    }

    public function export(Request $request)
    {
        $id_produk = $request->id_produk;
        $start = $request->start;
        $end = $request->end;

        return Excel::download(new MutasiExport($id_produk, $start, $end), 'Mutasi Produk (' . $start . '-' . $end . ').xlsx');
    }
}
