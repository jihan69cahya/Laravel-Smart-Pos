<?php

namespace App\Helpers;

use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Helper
{
    public static function insertLog($aksi, $deskripsi)
    {
        LogAktivitas::create([
            'id_user' => Auth::user()->id,
            'nama_user' => Auth::user()->nama,
            'tanggal' => Carbon::now(),
            'status' => $aksi,
            'keterangan' => $deskripsi
        ]);
    }
}
