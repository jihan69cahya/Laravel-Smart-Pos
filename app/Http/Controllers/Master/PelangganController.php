<?php

namespace App\Http\Controllers\Master;

use App\Helpers\Helper;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pelanggan::get();

            return DataTables::of($data)
                ->editColumn('jenis_kelamin', function ($row) {
                    if ($row->jenis_kelamin == 'L') {
                        return '<span class="badge badge-primary">Laki-laki</span>';
                    } elseif ($row->jenis_kelamin == 'P') {
                        return '<span class="badge badge-danger">Perempuan</span>';
                    } else {
                        return '<span class="badge badge-secondary">Tidak Diketahui</span>';
                    }
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
                ->rawColumns(['aksi', 'jenis_kelamin'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Master', 'url' => 'javascript:void(0)'],
            ['title' => 'Pelanggan', 'url' => null]
        ];
        return view('master.pelanggan.index', compact('breadcrumb'));
    }
    public function edit($id)
    {
        $data = Pelanggan::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'telepon' => [
                'required',
                'numeric',
                'min:10',
                Rule::unique('m_pelanggan', 'telp')->whereNull('deleted_at'),
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            Pelanggan::create([
                'nama' => $request->nama,
                'no_member' => Helper::generateNomorMember(),
                'telp' => $request->telepon,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data pelanggan (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Pelanggan telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MASTER PELANGGAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'telepon' => [
                'required',
                'numeric',
                'min:10',
                Rule::unique('m_pelanggan', 'telp')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = Pelanggan::find($id);
            $data->nama = $request->nama;
            $data->telp = $request->telepon;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->alamat = $request->alamat;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data pelanggan (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Pelanggan berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MASTER PELANGGAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Pelanggan::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data pelanggan (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Pelanggan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MASTER PELANGGAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
