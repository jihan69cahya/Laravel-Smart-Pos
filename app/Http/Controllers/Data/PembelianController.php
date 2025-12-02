<?php

namespace App\Http\Controllers\Data;

use Carbon\Carbon;
use App\Models\Stok;
use App\Helpers\Date;
use App\Helpers\Money;
use App\Helpers\Helper;
use App\Models\LogStok;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Services\StokService;
use App\Models\PembelianDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    private $stokService;

    public function __construct()
    {
        $this->stokService = new StokService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id_supplier = $request->id_supplier;
            $range = Helper::splitDateRange($request->date_range);
            $start = $range['start'];
            $end = $range['end'];

            $data = Pembelian::with('relSupplier')
                ->when($id_supplier, function ($q) use ($id_supplier) {
                    $q->where('id_supplier', $id_supplier);
                })
                ->when($start && $end, function ($q) use ($start, $end) {
                    $q->whereBetween('tanggal', [$start, $end]);
                })
                ->orderBy('tanggal')
                ->get();

            return DataTables::of($data)
                ->editColumn("tanggal", function ($row) {
                    return Date::format($row->tanggal, 2);
                })
                ->addColumn("supplier", function ($row) {
                    return $row->relSupplier->nama ?? '-';
                })
                ->addColumn("status", function ($row) {
                    return Helper::statusPenerimaanBadge($row->status_penerimaan);
                })
                ->addColumn('tagihan', function ($row) {
                    $sub_total       = Money::stringToRupiah($row->sub_total ?? 0);
                    $pajak           = Money::stringToRupiah($row->pajak ?? 0);
                    $potongan        = Money::stringToRupiah($row->potongan ?? 0);
                    $biaya_tambahan  = Money::stringToRupiah($row->biaya_tambahan ?? 0);
                    $total_tagihan   = Money::stringToRupiah($row->total_tagihan ?? 0);

                    return "
                            <div>
                                <div>Sub Total: <b>{$sub_total}</b></div>
                                <div>Pajak: <b>{$pajak}</b></div>
                                <div>Potongan: <b>{$potongan}</b></div>
                                <div>Biaya Tambahan: <b>{$biaya_tambahan}</b></div>
                                <div>Total Tagihan: <b>{$total_tagihan}</b></div>
                            </div>
                        ";
                })
                ->editColumn("keterangan", function ($row) {
                    return $row->keterangan ?? '-';
                })
                ->addColumn("aksi", function ($row) {
                    $id = encrypt($row->id);

                    $btnDetail = "<button class='btn btn-dark btn-xs' onclick='detail(\"$id\")'>
                    <i class='fa fa-list'></i> Detail
                  </button>";

                    $btnEdit = "<button class='btn btn-warning btn-xs text-dark' onclick='edit(\"$id\")'>
                    <i class='fa fa-edit'></i> Edit
                </button>";

                    $btnHapus = "<button class='btn btn-danger btn-xs' onclick='hapus_data(\"$id\")'>
                    <i class='fa fa-trash-o'></i> Hapus
                </button>";

                    $btnTerima = "<button class='btn btn-primary btn-xs' onclick='terima(\"$id\")'>
                    <i class='fa fa-check'></i> Terima
                  </button>";

                    $buttons = [];

                    if ($row->status_penerimaan == 0) {
                        $buttons = [$btnEdit, $btnHapus, $btnTerima, $btnDetail];
                    } elseif ($row->status_penerimaan == 2) {
                        $buttons = [$btnTerima, $btnDetail];
                    } elseif ($row->status_penerimaan == 1) {
                        $buttons = [$btnDetail];
                    } else {
                        return "<span class='badge badge-light'>Unknown</span>";
                    }

                    return "<div class='btn-group btn-group-xs' role='group'>" . implode('', $buttons) . "</div>";
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'tagihan', 'status'])
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Pembelian', 'url' => null]
        ];
        $data['supplier'] = Supplier::all();
        return view('data.pembelian.index', compact('breadcrumb', 'data'));
    }

    public function create(Request $request)
    {
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Pembelian', 'url' => route('data.pembelian.index')],
            ['title' => 'Tambah', 'url' => null]
        ];
        $data['supplier'] = Supplier::all();
        return view('data.pembelian.tambah', compact('breadcrumb', 'data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'supplier' => 'required|exists:m_supplier,id',
            'no_faktur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $produkData = $request->produk_data;

        if (!$produkData) {
            return response()->json([
                'error' => 'Produk tidak ada!'
            ], status: 422);
        }

        $produkData = json_decode($request->produk_data, true);

        if (empty($produkData)) {
            return response()->json([
                'error' => 'Data produk tidak valid'
            ], 422);
        }

        DB::beginTransaction();
        try {

            $pembelian = Pembelian::create([
                'id_supplier' => $request->supplier,
                'no_faktur' => $request->no_faktur,
                'tanggal' => $request->tanggal,
                'sub_total' => $request->sub_total,
                'pajak' => $request->pajak,
                'nilai_pajak' => $request->nilai_pajak,
                'potongan' => $request->potongan ? Money::rupiahToString($request->potongan) : 0,
                'biaya_tambahan' => $request->biaya_tambahan ? Money::rupiahToString($request->biaya_tambahan) : 0,
                'total_tagihan' => $request->total_tagihan,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($produkData as $item) {
                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id,
                    'id_produk' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'total' => $item['total'],
                ]);
            }

            Helper::insertLog('Tambah Data', deskripsi: 'Menambah data pembelian (' . $request->no_faktur . ')');

            DB::commit();
            return response()->json(['success' => 'Pembelian telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TAMBAH DATA PEMBELIAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Pembelian', 'url' => route('data.pembelian.index')],
            ['title' => 'Edit', 'url' => null]
        ];
        $data['supplier'] = Supplier::all();
        $data['id'] = $id;
        $data['pembelian'] = Pembelian::find(decrypt($id));
        return view('data.pembelian.edit', compact('breadcrumb', 'data'));
    }

    public function detail($id)
    {
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Pembelian', 'url' => route('data.pembelian.index')],
            ['title' => 'Detail', 'url' => null]
        ];
        $data['id'] = $id;
        $data['pembelian'] = Pembelian::with('relSupplier', 'relDetail.relProduk.relSatuan')->find(decrypt($id));
        return view('data.pembelian.detail', compact('breadcrumb', 'data'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'supplier' => 'required|exists:m_supplier,id',
            'no_faktur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $produkData = $request->produk_data;

        if (!$produkData) {
            return response()->json([
                'error' => 'Produk tidak ada!'
            ], status: 422);
        }

        $produkData = json_decode($request->produk_data, true);

        if (empty($produkData)) {
            return response()->json([
                'error' => 'Data produk tidak valid'
            ], 422);
        }

        DB::beginTransaction();
        try {

            $pembelian = Pembelian::findOrFail($id);


            $pembelian->update([
                'id_supplier'     => $request->supplier,
                'no_faktur'       => $request->no_faktur,
                'tanggal'         => $request->tanggal,
                'sub_total'       => $request->sub_total,
                'pajak'           => $request->pajak,
                'nilai_pajak'     => $request->nilai_pajak,
                'potongan'        => $request->potongan ? Money::rupiahToString($request->potongan) : 0,
                'biaya_tambahan'  => $request->biaya_tambahan ? Money::rupiahToString($request->biaya_tambahan) : 0,
                'total_tagihan'   => $request->total_tagihan,
                'keterangan'      => $request->keterangan,
            ]);

            PembelianDetail::where('id_pembelian', $id)->delete();
            foreach ($produkData as $item) {
                PembelianDetail::create([
                    'id_pembelian' => $id,
                    'id_produk'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga'        => $item['harga'],
                    'total'        => $item['total'],
                ]);
            }

            Helper::insertLog('Edit Data', deskripsi: 'Menambah data pembelian (' . $request->no_faktur . ')');

            DB::commit();
            return response()->json(['success' => 'Pembelian telah diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EDIT DATA PEMBELIAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            $pembelian = Pembelian::find($id);
            $no_faktur = $pembelian->no_faktur;
            $pembelian->delete();

            Helper::insertLog('Hapus Data', 'Menghapus data pembelian (' . $no_faktur . ')');

            DB::commit();
            return response()->json(['success' => 'Pembelian berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HAPUS DATA PEMBELIAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function penerimaan($id)
    {
        $breadcrumb = [
            ['title' => 'Data', 'url' => 'javascript:void(0)'],
            ['title' => 'Pembelian', 'url' => route('data.pembelian.index')],
            ['title' => 'Penerimaan', 'url' => null]
        ];
        $data['id'] = $id;
        $data['pembelian'] = Pembelian::with('relSupplier')->find(decrypt($id));
        return view('data.pembelian.penerimaan', compact('breadcrumb', 'data'));
    }

    public function simpanPenerimaan(Request $request)
    {
        DB::beginTransaction();

        try {
            $id_pembelian = decrypt($request->id_pembelian);
            $detail = json_decode($request->detail, true);

            foreach ($detail as $item) {
                $id = $item['id'];
                $id_log_stok = $item['id_log_stok'];
                $jumlah_terima = $item['jumlah_terima'];
                $produk_id = $item['produk_id'];

                $stok = Stok::where('id_produk', $produk_id)->first();
                if (!$stok) {
                    $stok = $this->stokService->initStok($produk_id);
                }

                $detail = PembelianDetail::find($id);

                if ($id_log_stok) {
                    $update_jumlah_terima = $detail->jumlah_terima + $jumlah_terima;
                    $log_stok = LogStok::find($id_log_stok);
                    $data = [
                        'tanggal' => Carbon::now(),
                        'id_produk' => $produk_id,
                        'unit_masuk' => $update_jumlah_terima,
                        'unit_keluar' => 0,
                        'status' => 'PB',
                        'keterangan' => null,
                    ];
                    $service = $this->stokService->updateLogStok($log_stok, $data);
                    $id_log_stok = $service['id_log_stok'];
                } else {
                    $update_jumlah_terima = $jumlah_terima;
                    $dataLogStok = [
                        'tanggal' => Carbon::now(),
                        'unit_masuk' => $update_jumlah_terima,
                        'unit_keluar' => 0,
                        'status' => 'PB',
                        'keterangan' => null
                    ];
                    $service = $this->stokService->insertLogStok($stok, $dataLogStok);
                    $id_log_stok = $service['id_log_stok'];
                }

                $detail->jumlah_terima = $update_jumlah_terima;
                $detail->tanggal_terima = Carbon::now();
                $detail->id_log_stok = $id_log_stok;
                $detail->save();
            }

            $pembelian = Pembelian::find($id_pembelian);

            $details = PembelianDetail::where('id_pembelian', $id_pembelian)->get();

            $all = $details->every(fn($d) => $d->jumlah_terima >= $d->jumlah);
            $any = $details->contains(fn($d) => $d->jumlah_terima > 0);

            $pembelian->status_penerimaan = $all ? 1 : ($any ? 2 : 0);
            $pembelian->tanggal_penerimaan = Carbon::now();
            $pembelian->save();

            DB::commit();
            return response()->json(['success' => 'Penerimaan barang telah dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PENERIMAAN DATA PEMBELIAN ERROR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
