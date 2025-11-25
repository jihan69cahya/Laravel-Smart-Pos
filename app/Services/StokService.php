<?php

namespace App\Services;

use App\Models\LogStok;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Translation\t;

class StokService
{
    public function initStok($id_produk)
    {
        try {
            DB::beginTransaction();

            $cek = Stok::where('id_produk', $id_produk)->exists();

            if (!$cek) {
                $stokAkhir = LogStok::where('id_produk', $id_produk)
                    ->selectRaw('SUM(unit_masuk) - SUM(unit_keluar) as stok')
                    ->value('stok');

                $stok = Stok::create([
                    'id_produk' => $id_produk,
                    'stok' => $stokAkhir ?? 0
                ]);

                DB::commit();
                return $stok;
            } else {
                Log::info('STOK SERVICE INIT STOK : Stok produk dengan id ' . $id_produk . ' sudah ada.');
                return [
                    'code' => 500,
                    'message' => 'Stok produk sudah diinisialisasi sebelumnya.'
                ];
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('STOK SERVICE INIT STOK :' . $th->getMessage() . ' line ' . $th->getLine());
            return [
                'code' => 500,
                'message' => 'Terjadi kesalahan, coba lagi nanti.'
            ];
        }
    }

    public function stokOpname(Stok $stok)
    {
        $hitung_stok = LogStok::where('id_produk', $stok->id_produk)
            ->selectRaw('SUM(unit_masuk) - SUM(unit_keluar) as stok')
            ->value('stok');

        try {
            DB::beginTransaction();

            $stok->lockForUpdate();
            $stok_dihitung = $hitung_stok ?? 0;
            $stok->update([
                'stok' => $stok_dihitung
            ]);
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Stok opname berhasil dilakukan.',
                'data' => [
                    'id_produk' => $stok->id_produk,
                    'stok_sekarang' => $stok_dihitung
                ]
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('STOK SERVICE STOK OPNAME :' . $th->getMessage() . ' line ' . $th->getLine());
            return [
                'code' => 500,
                'message' => 'Terjadi kesalahan, coba lagi nanti.'
            ];
        }
    }

    public function insertLogStok(Stok $stok, array $data)
    {
        try {
            DB::beginTransaction();

            $log = LogStok::create([
                'id_produk' => $stok->id_produk,
                'tanggal' => $data['tanggal'],
                'unit_masuk' => $data['unit_masuk'] ?? 0,
                'unit_keluar' => $data['unit_keluar'] ?? 0,
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ])->id;

            $jumlah_stok = $stok->stok + ($data['unit_masuk'] ?? 0) - ($data['unit_keluar'] ?? 0);
            $stok->update([
                'stok' => $jumlah_stok
            ]);

            DB::commit();
            return [
                'code' => 200,
                'message' => 'success',
                'id_log_stok' => $log
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('STOK SERVICE INSERT TO LOGSTOK : ' . $th->getMessage() . ' line ' . $th->getLine());
            return [
                'code' => 500,
                'message' => 'Terjadi kesalahan, coba lagi nanti.'
            ];
        }
    }

    public function updateLogStok(LogStok $logStok, array $data)
    {
        try {
            DB::beginTransaction();

            if ((int) $logStok->id_produk !== (int) $data['id_produk']) {
                $old_stok = Stok::where('id_produk', $logStok->id_produk)->first();
                $logStok->delete();
                $this->stokOpname($old_stok);

                $new_stok = Stok::where('id_produk', $data['id_produk'])->first();
                if (!$new_stok) {
                    $new_stok = $this->initStok($data['id_produk']);
                }
                $dataLogStok = [
                    'tanggal' => $data['tanggal'],
                    'unit_masuk' => $data['unit_masuk'] ?? 0,
                    'unit_keluar' => $data['unit_keluar'] ?? 0,
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                ];
                $service = $this->insertLogStok($new_stok, $dataLogStok);
                $id_log_stok = $service['id_log_stok'];
            } else {
                $logStok->update([
                    'tanggal' => $data['tanggal'],
                    'unit_masuk' => $data['unit_masuk'] ?? 0,
                    'unit_keluar' => $data['unit_keluar'] ?? 0,
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                ]);
                $stok = Stok::where('id_produk', $logStok->id_produk)->first();
                $this->stokOpname($stok);
                $id_log_stok = $logStok->id;
            }

            DB::commit();
            return [
                'code' => 200,
                'message' => 'success',
                'id_log_stok' => $id_log_stok
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('STOK SERVICE UPDATE LOGSTOK : ' . $th->getMessage() . ' line ' . $th->getLine());
            return [
                'code' => 500,
                'message' => 'Terjadi kesalahan, coba lagi nanti.'
            ];
        }
    }

    public function deleteLogStok(Stok $stok, LogStok $logStok)
    {
        try {
            DB::beginTransaction();

            $logStok->delete();

            $this->stokOpname($stok);

            DB::commit();
            return [
                'code' => 200,
                'message' => 'success'
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('STOK SERVICE DELETE LOGSTOK : ' . $th->getMessage() . ' line ' . $th->getLine());
            return [
                'code' => 500,
                'message' => 'Terjadi kesalahan, coba lagi nanti.'
            ];
        }
    }
}
