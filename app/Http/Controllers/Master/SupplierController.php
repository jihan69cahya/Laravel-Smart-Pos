<?php

namespace App\Http\Controllers\Master;

use App\Helpers\Helper;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::get();
            return DataTables::of($data)
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $button = "<button class='btn btn-warning btn-xs text-dark' data-bs-toggle='modal'
                                data-bs-target='#modal' onclick='submit(\"$id\")'>
                                <i class='fa fa-edit'></i> Edit
                            </button>
                            <button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                                <i class='fa fa-trash-o'></i> Hapus
                            </button>";
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Master', 'url' => 'javascript:void(0)'],
            ['title' => 'Supplier', 'url' => null]
        ];
        return view('master.supplier.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = Supplier::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'string',
                Rule::unique('m_supplier', 'email')->whereNull('deleted_at'),
            ],
            'alamat' => 'required|string',
            'telepon' => 'nullable|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            Supplier::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'telp' => $request->telepon,
                'email' => $request->email,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data supplier (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Supplier telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MASTER SUPPLIER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'string',
                Rule::unique('m_supplier', 'email')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'alamat' => 'required|string',
            'telepon' => 'nullable|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = Supplier::find($id);
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->telp = $request->telepon;
            $data->email = $request->email;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data supplier (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Supplier berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MASTER SUPPLIER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Supplier::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data supplier (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Supplier berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MASTER SUPPLIER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
