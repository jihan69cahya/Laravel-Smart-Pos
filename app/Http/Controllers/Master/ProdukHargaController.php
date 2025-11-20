<?php

namespace App\Http\Controllers\Master;

use App\Helpers\Date;
use App\Helpers\Money;
use App\Models\Produk;
use App\Helpers\Helper;
use App\Models\ProdukHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProdukHargaController extends Controller
{
    public function show($id)
    {
        $id = decrypt($id);
        $data = ProdukHarga::where('id_produk', $id)->orderBy('tanggal', 'desc')->get();
        return DataTables::of(source: $data)
            ->editColumn("tanggal", function ($row) {
                return Date::format($row->tanggal, 1);
            })
            ->editColumn("harga", function ($row) {
                return Money::stringToRupiah($row->harga);
            })
            ->editColumn("harga_diskon", function ($row) {
                return $row->harga_diskon ? Money::stringToRupiah($row->harga_diskon) : '-';
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

    public function edit($id)
    {
        $data = ProdukHarga::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required',
            'tanggal' => 'required|date',
            'harga' => 'required',
            'harga_diskon' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $id_produk = decrypt($request->id_produk);
            ProdukHarga::create([
                'id_produk' => $id_produk,
                'tanggal' => $request->tanggal,
                'harga' => Money::rupiahToString($request->harga),
                'harga_diskon' => $request->harga_diskon ? Money::rupiahToString($request->harga_diskon) : null,
            ]);

            $nama_produk = Produk::find($id_produk)->nama;
            Helper::insertLog('Tambah Data', 'Menambah data harga produk (' . $nama_produk . ')');

            DB::commit();
            return response()->json(['success' => 'Harga Produk telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MASTER HARGA PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'id_produk' => 'required',
            'tanggal' => 'required|date',
            'harga' => 'required',
            'harga_diskon' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = ProdukHarga::find($id);
            $data->tanggal = $request->tanggal;
            $data->harga = Money::rupiahToString($request->harga);
            $data->harga_diskon = $request->harga_diskon ? Money::rupiahToString($request->harga_diskon) : null;
            $data->save();

            $nama_produk = Produk::find(decrypt($request->id_produk))->nama;
            Helper::insertLog('Edit Data', 'Mengubah data harga produk (' . $nama_produk . ')');

            DB::commit();
            return response()->json(['success' => 'Harga Produk berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MASTER HAGRA PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = ProdukHarga::findOrFail($id);
            $nama_produk = Produk::find($data->id_produk)->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data harga produk (' . $nama_produk . ')');

            DB::commit();
            return response()->json(['success' => 'Harga Produk berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MASTER HARGA PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
