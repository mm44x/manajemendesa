<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\AnggotaKeluarga;

use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'bendahara') {
                abort(403, 'Anda tidak punya akses ke modul ini.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = KartuKeluarga::with('desa');

        if ($request->filled('cari')) {
            $query->where('kepala_keluarga', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('no_kk')) {
            $query->where('no_kk', 'like', '%' . $request->no_kk . '%');
        }

        if ($request->filled('sort_no_kk')) {
            $sort = in_array($request->sort_no_kk, ['asc', 'desc']) ? $request->sort_no_kk : 'asc';
            $query->orderBy('no_kk', $sort);
        }

        $data = $query->paginate(10)->appends(request()->query());

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
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin tidak boleh mengubah data KK.');
        }
        $validated = $request->validate([
            'no_kk' => 'required|digits_between:10,20|numeric|unique:kartu_keluargas,no_kk',
            'kepala_keluarga' => 'required|string',
            'alamat' => 'required|string',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'desa_id' => 'required|exists:wilayah,kode',
            'kode_pos' => 'required|digits_between:4,6|numeric',
            'tanggal_terbit' => 'required|date',
        ]);


        $kk = KartuKeluarga::create($validated);

        AnggotaKeluarga::create([
            'kartu_keluarga_id' => $kk->id,
            'nik' => null,
            'nama' => $kk->kepala_keluarga,
            'jenis_kelamin' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'hubungan' => 'Kepala Keluarga',
            'agama' => null,
            'pendidikan' => null,
            'pekerjaan' => null,
        ]);

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
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin tidak boleh mengubah data KK.');
        }
        $validated = $request->validate([
            'no_kk' => 'required|digits_between:10,20|numeric|unique:kartu_keluargas,no_kk' . ($id ? ',' . $id : ''),
            'kepala_keluarga' => 'required|string',
            'alamat' => 'required|string',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'desa_id' => 'required|exists:wilayah,kode',
            'kode_pos' => 'required|digits_between:4,6|numeric',
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
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin tidak boleh mengubah data KK.');
        }
        KartuKeluarga::findOrFail($id)->delete();
        return redirect()->route('kartu-keluarga.index')->with('success', 'Data berhasil dihapus');
    }
}
