<?php

namespace App\Http\Controllers\Manajemen;

use App\Models\Menu;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::get();

            return DataTables::of($data)
                ->editColumn("icon", function ($row) {
                    return $row->icon ? '<i class="' . e($row->icon) . '"></i>' : '-';
                })
                ->editColumn("route", function ($row) {
                    return $row->route ?? '-';
                })
                ->addColumn("parent", function ($row) {
                    return $row->relParent ? $row->relParent->nama : 'Parent';
                })
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $button = "<button class='btn btn-warning btn-xs text-dark' data-bs-toggle='modal'
                                data-bs-target='#modal' onclick='submit(\"$id\")'>
                                <i class='fa fa-edit'></i> Edit
                            </button>
                            <button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                                <i class='fa fa-trash-o'></i> Delete
                            </button>";
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'icon', 'link'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Manejemen', 'url' => 'javascript:void(0)'],
            ['title' => 'Menu', 'url' => null]
        ];
        return view('manajemen.menu.index', compact('breadcrumb'));
    }

    public function edit($id)
    {
        $data = Menu::find(decrypt($id));
        $data->id_parent = $data->id_parent ?? 0;
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'route' => 'nullable|string',
            'icon' => 'nullable|string',
            'id_parent' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            Menu::create([
                'nama' => $request->nama,
                'route' => $request->route,
                'icon' => $request->icon,
                'id_parent' => $request->id_parent == 0 ? null : $request->id_parent,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data menu (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Menu telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MANAJEMEN MENU ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'route' => 'nullable|string',
            'icon' => 'nullable|string',
            'id_parent' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        try {
            $data = Menu::find($id);
            $data->nama = $request->nama;
            $data->route = $request->route;
            $data->icon = $request->icon;
            $data->id_parent = $request->id_parent == 0 ? null : $request->id_parent;
            $data->save();

            Helper::insertLog('Edit Data', 'Mengubah data menu (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Menu berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MANAJEMEN MENU ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Menu::findOrFail($id);
            $child = Menu::where('id_parent', $data->id)->count();
            if ($child > 0) {
                return response()->json(['error' => 'Menu ini merupakan parent dari menu lain.']);
            }
            $nama = $data->nama;
            $data->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data menu (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Menu berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MANAJEMEN MENU ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
