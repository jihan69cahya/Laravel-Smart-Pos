<?php

namespace App\Http\Controllers\Data;

use App\Helpers\Date;
use App\Models\Produk;
use App\Helpers\Helper;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\LogStok;
use App\Models\Stok;
use App\Services\StokService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SaldoAwalController extends Controller
{

    private $stokService;

    public function __construct()
    {
        $this->stokService = new StokService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SaldoAwal::with('relProduk')->get();

            return DataTables::of($data)
                ->editColumn("tanggal", function ($row) {
                    return Date::format($row->tanggal, 2);
                })
                ->addColumn("produk", function ($row) {
                    return $row->relProduk->nama ?? '-';
                })
                ->editColumn("keterangan", function ($row) {
                    return $row->keterangan ?? '-';
                })
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $button = "<button class='btn btn-warning btn-xs text-dark' data-bs-toggle='modal'
                                data-bs-target='#modal' onclick='submit(\"$id\")'>
                                <i class='fa fa-edit'></i> Edit
                            </button>
                            <button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
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
            ['title' => 'Saldo Awal', 'url' => null]
        ];
        return view('data.saldo_awal.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = SaldoAwal::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'produk' => 'required|exists:m_produk,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $stok = Stok::where('id_produk', $request->produk)->first();
            if (!$stok) {
                $stok = $this->stokService->initStok($request->produk);
            }
            $dataLogStok = [
                'tanggal' => $request->tanggal,
                'unit_masuk' => $request->jumlah,
                'unit_keluar' => 0,
                'status' => 'SA',
                'keterangan' => $request->keterangan,
            ];
            $service = $this->stokService->insertLogStok($stok, $dataLogStok);
            $id_log_stok = $service['id_log_stok'];

            SaldoAwal::create([
                'tanggal' => $request->tanggal,
                'id_produk' => $request->produk,
                'jumlah' => $request->jumlah,
                'id_log_stok' => $id_log_stok,
                'keterangan' => $request->keterangan,
            ]);

            $nama_produk = Produk::find($request->produk)->nama ?? '';
            Helper::insertLog('Tambah Data', 'Menambah data saldo awal (' . $nama_produk . ')');

            DB::commit();
            return response()->json(['success' => 'Saldo awal telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH DATA SALDO AWAL ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'produk' => 'required|exists:m_produk,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $log_stok = LogStok::find($request->id_log_stok);
            $data = [
                'tanggal' => $request->tanggal,
                'id_produk' => $request->produk,
                'unit_masuk' => $request->jumlah,
                'unit_keluar' => 0,
                'status' => 'SA',
                'keterangan' => $request->keterangan,
            ];
            $service = $this->stokService->updateLogStok($log_stok, $data);
            $id_log_stok = $service['id_log_stok'];
            $update = SaldoAwal::find($id);
            $update->tanggal = $request->tanggal;
            $update->id_produk = $request->produk;
            $update->jumlah = $request->jumlah;
            $update->id_log_stok = $id_log_stok;
            $update->keterangan = $request->keterangan;
            $update->save();

            $nama = Produk::find($request->produk)->nama ?? '';
            Helper::insertLog('Edit Data', 'Mengubah data saldo awal (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Saldo Awal berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT DATA SALDO AWAL ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $saldo_awal = SaldoAwal::find($id);
            $nama = Produk::find($saldo_awal->id_produk)->nama ?? '';
            $saldo_awal->delete();

            $log_stok = LogStok::find($saldo_awal->id_log_stok);
            $stok = Stok::where('id_produk', $log_stok->id_produk)->first();

            $this->stokService->deleteLogStok($stok, $log_stok);

            Helper::insertLog('Hapus Data', 'Menghapus data saldo awal (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Saldo Awal berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS DATA SALDO AWAL ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
