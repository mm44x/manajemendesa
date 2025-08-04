<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class AnggotaKeluargaController extends Controller
{
    public function indexByKK($kk_id, Request $request)
    {
        $kk = KartuKeluarga::findOrFail($kk_id);

        $query = AnggotaKeluarga::where('kartu_keluarga_id', $kk_id);

        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . $request->nik . '%');
        }

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        $anggota = $query->orderBy('nama')->paginate(10)->appends($request->query());

        return view('anggota_keluarga.index', compact('kk', 'anggota'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak boleh mengubah data.');
        }

        $validated = $request->validate([
            'kartu_keluarga_id' => 'required|exists:kartu_keluargas,id',
            'nik' => 'required|numeric|digits_between:10,20|unique:anggota_keluargas,nik',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|exists:wilayah,kode',
            'tanggal_lahir' => 'required|date',
            'hubungan' => 'required|string',
            'agama' => 'required|string',
            'pendidikan' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        AnggotaKeluarga::create($validated);

        return redirect()->back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function show(AnggotaKeluarga $anggotaKeluarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function edit(AnggotaKeluarga $anggotaKeluarga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak boleh mengubah data.');
        }

        $validated = $request->validate([
            'kartu_keluarga_id' => 'required|exists:kartu_keluargas,id',
            'nik' => 'required|numeric|digits_between:10,20|unique:anggota_keluargas,nik,' . $id,
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|exists:wilayah,kode',
            'tanggal_lahir' => 'required|date',
            'hubungan' => 'required|string',
            'agama' => 'required|string',
            'pendidikan' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        AnggotaKeluarga::findOrFail($id)->update($validated);

        return redirect()->back()->with('success', 'Data anggota berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak boleh menghapus data.');
        }

        $anggota = AnggotaKeluarga::findOrFail($id);
        $anggota->delete();

        return redirect()->back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
