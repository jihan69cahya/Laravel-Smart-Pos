<?php

namespace App\Http\Controllers\Master;

use App\Helpers\Helper;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::get();

            return DataTables::of($data)
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
            ['title' => 'Master', 'url' => 'javascript:void(0)'],
            ['title' => 'Kategori', 'url' => null]
        ];
        return view('manajemen.kategori.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = Kategori::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            Kategori::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data kategori (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Kategori telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MASTER KATEGORI ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = Kategori::find($id);
            $data->nama = $request->nama;
            $data->keterangan = $request->keterangan;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data kategori (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Kategori berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MASTER KATEGORI ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Kategori::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data kategori (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Kategori berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MASTER KATEGORI ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
