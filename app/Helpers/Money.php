<?php

namespace App\Helpers;

class Money
{

    public static function rupiahToString($rp)
    {
        $result = substr(str_replace(".", "", $rp), 3);
        return $result;
    }

    public static function stringToRupiah($rp)
    {
        $floatRupiah = (float) $rp;
        $floatRupiah = ($floatRupiah <= 0) ? abs($floatRupiah) : $floatRupiah;
        return 'Rp. ' . number_format($floatRupiah, 0, '', '.');
    }

    public static function formatNumber($number, $decimal = 0)
    {
        return number_format($number, $decimal, ',', '.');
    }
}
