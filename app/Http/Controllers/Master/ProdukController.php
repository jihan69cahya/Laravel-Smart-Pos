<?php

namespace App\Http\Controllers\Master;

use App\Helpers\Money;
use App\Models\Produk;
use App\Models\Satuan;
use App\Helpers\Helper;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Produk::with('relKategori', 'relSatuan')->get();

            return DataTables::of(source: $data)
                ->editColumn('foto', function ($row) {
                    if (!$row->foto) {
                        $path = asset('assets/images/product/default.png');
                    } else {
                        $path = asset('storage/' . $row->foto);
                    }

                    return '<img src="' . $path . '" width="100" height="100" style="object-fit:cover;border-radius:4px;">';
                })
                ->addColumn("harga_terbaru", function ($row) {
                    if (!$row->hargaTerbaru) {
                        return '-';
                    }

                    $harga = $row->hargaTerbaru->harga;
                    $harga_diskon = $row->hargaTerbaru->harga_diskon;

                    if ($harga_diskon && $harga_diskon < $harga) {
                        return '
                                    <span style="text-decoration: line-through; color: #888;">' . Money::stringToRupiah($harga) . '</span><br>
                                    <span style="color: #e74c3c; font-weight: bold;">' . Money::stringToRupiah($harga_diskon) . '</span>
                                ';
                    }

                    return Money::stringToRupiah($harga);
                })
                ->addColumn("kategori", function ($row) {
                    return $row->relKategori->nama ?? '-';
                })
                ->addColumn("satuan", function ($row) {
                    return $row->relSatuan->nama ?? '-';
                })
                ->editColumn("deskripsi", function ($row) {
                    return $row->deskripsi ?? '-';
                })
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);
                    $url = route('master.produk.harga', ['id' => $id]);
                    $button = "<button class='btn btn-warning btn-xs text-dark' data-bs-toggle='modal'
                                data-bs-target='#modal' onclick='submit(\"$id\")'>
                                <i class='fa fa-edit'></i> Edit
                            </button>
                            <button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                                <i class='fa fa-trash-o'></i> Hapus
                            </button>
                            <button class='btn btn-dark btn-xs' onclick='produk_harga(\"$url\")'>
                                <i class='fa fa-dollar'></i> Harga
                            </button>
                            ";
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'harga_terbaru', 'foto'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Master', 'url' => 'javascript:void(0)'],
            ['title' => 'Produk', 'url' => null]
        ];
        $data['kategori'] = Kategori::all();
        $data['satuan'] = Satuan::all();
        return view('master.produk.index', compact('breadcrumb', 'data'));
    }

    public function edit($id)
    {
        $data = Produk::find(decrypt($id));
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('m_produk', 'kode')->whereNull('deleted_at'),
            ],
            'nama' => 'required|string|max:255',
            'kategori' => 'required|numeric|exists:m_kategori,id',
            'satuan' => 'required|numeric|exists:m_satuan,id',
            'stok_minimal' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        $foto = $request->hasFile('foto')
            ? Helper::compressAndUpload($request->file('foto'), 'foto_produk')
            : null;

        try {
            Produk::create([
                'id_kategori' => $request->kategori,
                'id_satuan' => $request->satuan,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'foto' => $foto,
                'deskripsi' => $request->deskripsi,
                'stok_minimal' => $request->stok_minimal,
            ]);

            Helper::insertLog('Tambah Data', 'Menambah data produk (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Produk telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH MASTER PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('m_produk', 'kode')
                    ->ignore(decrypt($id))
                    ->whereNull('deleted_at')
            ],
            'nama' => 'required|string|max:255',
            'kategori' => 'required|numeric|exists:m_kategori,id',
            'satuan' => 'required|numeric|exists:m_satuan,id',
            'stok_minimal' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        $produk = Produk::find(decrypt($id));

        if ($request->hasFile('foto')) {
            if (!empty($produk->foto) && Storage::exists('public/' . $produk->foto)) {
                Storage::delete('public/' . $produk->foto);
            }

            $foto = Helper::compressAndUpload($request->file('foto'), 'foto_produk');
        } else {
            $foto = $produk->foto;
        }

        try {
            $produk->update([
                'id_kategori' => $request->kategori,
                'id_satuan' => $request->satuan,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'foto' => $foto,
                'deskripsi' => $request->deskripsi,
                'stok_minimal' => $request->stok_minimal,
            ]);

            Helper::insertLog('Edit Data', 'Mengubah data produk (' . $request->nama . ')');

            DB::commit();
            return response()->json(['success' => 'Produk telah diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT MASTER PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $data = Produk::findOrFail($id);
            $nama = $data->nama;
            $foto = $data->foto;
            $data->delete();

            if (!empty($foto) && Storage::exists('public/' . $foto)) {
                Storage::delete('public/' . $foto);
            }

            Helper::insertLog('Hapus Data', 'Menghapus data produk (' . $nama . ')');

            DB::commit();
            return response()->json(['success' => 'Produk berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS MASTER PRODUK ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
