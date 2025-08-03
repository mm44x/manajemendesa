<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = DB::table('wilayah')
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 2")
            ->get();
        return response()->json($provinsi);
    }

    public function getKabupaten(Request $request)
    {
        $prefix = $request->kode_provinsi;
        $kabupaten = DB::table('wilayah')
            ->where('kode', 'like', "$prefix.%")
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 4")
            ->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $prefix = $request->kode_kabupaten;
        $kecamatan = DB::table('wilayah')
            ->where('kode', 'like', "$prefix.%")
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 6")
            ->get();
        return response()->json($kecamatan);
    }

    public function getDesa(Request $request)
    {
        $prefix = $request->kode_kecamatan;
        $desa = DB::table('wilayah')
            ->where('kode', 'like', "$prefix.%")
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 10")
            ->get();
        return response()->json($desa);
    }
}

