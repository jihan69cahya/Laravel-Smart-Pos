<?php

namespace App\Http\Controllers\Manajemen;

use App\Models\Role;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\MappingMenu;
use App\Models\Menu;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::get();

            return DataTables::of($data)
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $url = route('manajemen.role.mapping', ['id' => $id]);
                    $button = "<button class='btn btn-warning btn-xs text-dark' data-bs-toggle='modal'
                                data-bs-target='#modal' onclick='submit(\"$id\")'>
                                <i class='fa fa-edit'></i> Edit
                            </button>
                            <button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                                <i class='fa fa-trash-o'></i> Hapus
                            </button>
                            <button class='btn btn-dark btn-xs' onclick='mapping_menu(\"$url\")'>
                                <i class='fa fa-gears'></i> Mapping Menu
                            </button>
                            ";
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Manejemen', 'url' => 'javascript:void(0)'],
            ['title' => 'Role', 'url' => null]
        ];
        return view('manajemen.role.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = Role::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            Role::create([
                'nama' => $request->nama,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data role (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Role telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MANAJEMEN ROLE ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = Role::find($id);
            $data->nama = $request->nama;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data role (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Role berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MANAJEMEN ROLE ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Role::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data role (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Role berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MANAJEMEN ROLE ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function mappingMenu($id)
    {
        $id = decrypt($id);
        $breadcrumb = [
            ['title' => 'Manejemen', 'url' => 'javascript:void(0)'],
            ['title' => 'Role', 'url' => route('manajemen.role.index')],
            ['title' => 'Mapping Menu', 'url' => null],
        ];
        $role = Role::find($id)->nama;
        $menu = Menu::with('relParent')->get();
        $mapping = MappingMenu::where('id_role', $id)->pluck('id_menu')->toArray();
        return view('manajemen.role.mapping', compact('breadcrumb', 'id', 'role', 'menu', 'mapping'));
    }

    public function simpanMapping(Request $request, $id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {

            MappingMenu::where('id_role', $id)->delete();
            $nama = Role::find($id)->nama;

            if ($request->has('menu_ids')) {
                $data = [];
                foreach ($request->menu_ids as $menuId) {
                    $data[] = [
                        'id_role' => $id,
                        'id_menu' => $menuId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                MappingMenu::insert($data);
            }

            Helper::insertLog('Mapping menu', 'Mengatur menu untuk role (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Mapping berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MAPPING ROLE ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }
}
