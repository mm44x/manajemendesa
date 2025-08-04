<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Wilayah;


use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = AnggotaKeluarga::with('kartuKeluarga');

        // Pencarian
        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . $request->nik . '%');
        }

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('no_kk')) {
            $query->whereHas('kartuKeluarga', function ($q) use ($request) {
                $q->where('no_kk', 'like', '%' . $request->no_kk . '%');
            });
        }

        $anggota = $query->orderBy('kartu_keluarga_id')->paginate(10)->appends($request->query());
        foreach ($anggota as $item) {
            $item->nama_tempat_lahir = Wilayah::where('kode', $item->tempat_lahir)->value('nama') ?? '-';
        }

        return view('warga.index', compact('anggota'));
    }
}
