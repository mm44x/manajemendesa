<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;

use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = KartuKeluarga::with('desa');

        if ($request->has('cari')) {
            $query->where('kepala_keluarga', 'like', '%' . $request->cari . '%');
        }

        $data = $query->paginate(10)->appends(request()->query()); // ✅ penting: withQueryString biar pagination tetap bawa query pencarian

        return view('kartu_keluarga.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kartu_keluarga.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|unique:kartu_keluargas',
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'desa_id' => 'required|exists:wilayah,kode',
            'kode_pos' => 'required',
            'tanggal_terbit' => 'required|date',
        ]);

        KartuKeluarga::create($validated);

        return redirect()->route('kartu-keluarga.index')->with('success', 'Data KK berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_kk' => 'required|unique:kartu_keluargas,no_kk,' . $id,
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'desa_id' => 'required|exists:wilayah,kode',
            'kode_pos' => 'required',
            'tanggal_terbit' => 'required|date',
        ]);

        KartuKeluarga::findOrFail($id)->update($validated);

        return redirect()->route('kartu-keluarga.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        KartuKeluarga::findOrFail($id)->delete();
        return redirect()->route('kartu-keluarga.index')->with('success', 'Data berhasil dihapus');
    }
}
