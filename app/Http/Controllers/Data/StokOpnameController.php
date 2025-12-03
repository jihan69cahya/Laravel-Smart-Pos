<?php

namespace App\Http\Controllers\Data;

use App\Models\Stok;
use App\Helpers\Date;
use App\Models\Produk;
use App\Helpers\Helper;
use App\Models\LogStok;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use App\Services\StokService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StokOpnameController extends Controller
{
    private $stokService;

    public function __construct()
    {
        $this->stokService = new StokService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StokOpname::with('relProduk')->orderBy('tanggal', 'desc')->get();
            return DataTables::of($data)
                ->editColumn("tanggal", function ($row) {
                    return Date::format($row->tanggal, 2);
                })
                ->editColumn("produk", function ($row) {
                    return $row->relProduk->nama;
                })
                ->editColumn("keterangan", function ($row) {
                    return $row->keterangan ?? '-';
                })
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $button = "<button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                                <i class='fa fa-trash-o'></i> Hapus
                            </button>
                            ";
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Stok Opname', 'url' => null]
        ];
        return view('data.stok_opname.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = StokOpname::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'produk' => 'required|exists:m_produk,id',
            'jumlah_fisik' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $selisih = $request->stok - $request->jumlah_fisik;
            if ($selisih > 0) {
                $unit_masuk = 0;
                $unit_keluar = $selisih;
            } else {
                $unit_masuk = abs($selisih);
                $unit_keluar = 0;
            }

            $stok = Stok::where('id_produk', $request->produk)->first();
            if (!$stok) {
                $stok = $this->stokService->initStok($request->produk);
            }
            $dataLogStok = [
                'tanggal' => $request->tanggal,
                'unit_masuk' => $unit_masuk,
                'unit_keluar' => $unit_keluar,
                'status' => 'SO',
                'keterangan' => $request->keterangan,
            ];
            $service = $this->stokService->insertLogStok($stok, $dataLogStok);
            $id_log_stok = $service['id_log_stok'];

            StokOpname::create([
                'tanggal' => $request->tanggal,
                'id_produk' => $request->produk,
                'stok' => $request->stok,
                'fisik' => $request->jumlah_fisik,
                'selisih' => abs($selisih),
                'keterangan' => $request->keterangan,
                'id_log_stok' => $id_log_stok,
            ]);

            $nama_produk = Produk::find($request->produk)->nama ?? '';
            Helper::insertLog('Tambah Data', 'Menambah data stok opname tanggal ' . $request->tanggal . ' (' . $nama_produk . ')');

            DB::commit();
            return response()->json(['success' => 'Stok opname telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH DATA STOK OPNAME ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $stok_opname = StokOpname::find($id);
            $nama = Produk::find($stok_opname->id_produk)->nama ?? '';
            $tanggal = $stok_opname->tanggal;
            $stok_opname->delete();

            $log_stok = LogStok::find($stok_opname->id_log_stok);
            $stok = Stok::where('id_produk', $log_stok->id_produk)->first();

            $this->stokService->deleteLogStok($stok, $log_stok);

            Helper::insertLog('Hapus Data', 'Menghapus data stok opname tanggal ' . $tanggal . ' (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Stok opname berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS DATA STOK OPNAME ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
