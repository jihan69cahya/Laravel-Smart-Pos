<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public static function compressAndUpload($file, $folder, $maxWidth = 1280)
    {
        $filename = time() . '.png';
        $path = $file->getRealPath();

        $source = imagecreatefromstring(file_get_contents($path));
        $width  = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $ratio = $height / $width;
            $newWidth  = $maxWidth;
            $newHeight = intval($newWidth * $ratio);
        } else {
            $newWidth  = $width;
            $newHeight = $height;
        }

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagepng($thumb, null, 9);
        $imageData = ob_get_clean();

        Storage::put("public/$folder/" . $filename, $imageData);

        imagedestroy($source);
        imagedestroy($thumb);

        return "$folder/$filename";
    }

    public static function generateNomorMember()
    {
        $lastPelanggan = Pelanggan::withTrashed()->orderBy('id', 'desc')->first();

        if ($lastPelanggan && $lastPelanggan->no_member) {
            $parts = explode('-', $lastPelanggan->no_member);
            $lastNumber = intval(str_replace('P', '', $parts[0]));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $today = Carbon::now()->format('Ymd');
        return 'P' . $newNumber . '-' . $today;
    }

    public static function splitDateRange($range)
    {
        $parts = explode(' - ', $range);

        return [
            'start'  => trim($parts[0] ?? ''),
            'end' => trim($parts[1] ?? ''),
        ];
    }

    public static function statusPenerimaanBadge($status)
    {
        switch ($status) {
            case 1:
                return '<span class="badge badge-success">Diterima</span>';

            case 2:
                return '<span class="badge badge-warning">Diterima Sebagian</span>';

            case 0:
            default:
                return '<span class="badge badge-danger">Belum Diterima</span>';
        }
    }
}
