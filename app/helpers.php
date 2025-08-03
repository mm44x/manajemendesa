<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('getWilayahNama')) {
    function getWilayahNama($kode_desa, $level)
    {
        $kodeParts = explode('.', $kode_desa);

        if ($level === 'kecamatan') {
            $kode = implode('.', array_slice($kodeParts, 0, 3));
        } elseif ($level === 'kabupaten') {
            $kode = implode('.', array_slice($kodeParts, 0, 2));
        } elseif ($level === 'provinsi') {
            $kode = $kodeParts[0];
        } else {
            return '-';
        }

        return DB::table('wilayah')->where('kode', $kode)->value('nama') ?? '-';
    }
}
