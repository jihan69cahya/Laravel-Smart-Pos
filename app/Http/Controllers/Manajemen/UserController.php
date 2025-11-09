<?php

namespace App\Http\Controllers\Manajemen;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('relRole')->get();
            return DataTables::of($data)
                ->addColumn("role", function ($row) {
                    return $row->relRole->nama;
                })
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
            ['title' => 'Manejemen', 'url' => 'javascript:void(0)'],
            ['title' => 'User', 'url' => null]
        ];
        $role = Role::all();
        return view('manajemen.user.index', compact('breadcrumb', 'role'));
    }

    public function edit($id)
    {
        $data = User::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'email' => 'required|string|email',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:m_role,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => $request->role
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data user (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'User telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MANAJEMEN USER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'email' => 'required|string|email',
            'password' => 'nullable|min:6',
            'role' => 'required|string|exists:m_role,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = User::find($id);
            $data->nama = $request->nama;
            $data->username = $request->username;
            $data->email = $request->email;
            if ($request->password) {
                $data->password = Hash::make($request->password);
            }
            $data->id_role = $request->role;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data user (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'User berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MANAJEMEN USER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = User::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data user (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MANAJEMEN USER ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
