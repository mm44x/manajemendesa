<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'bendahara') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Iuran::query();

        if ($request->filled('nama_iuran')) {
            $query->where('nama_iuran', 'like', '%' . $request->nama_iuran . '%');
        }

        $iurans = $query->latest()->paginate(10)->withQueryString();
        $kks = KartuKeluarga::all();

        return view('iuran.index', compact('iurans', 'kks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_iuran'    => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'tipe'          => 'required|in:sekali,mingguan,bulanan',
            'jenis_setoran' => 'required|in:tetap,bebas',
            'nominal'       => 'nullable|integer|min:0',
            'peserta'       => 'required|array',
            'peserta.*'     => 'exists:kartu_keluargas,id',
        ]);

        $iuranData = [
            'nama_iuran'    => $data['nama_iuran'],
            'deskripsi'     => $data['deskripsi'],
            'tipe'          => $data['tipe'],
            'jenis_setoran' => $data['jenis_setoran'],
            'nominal'       => $data['nominal'],
            'created_by'    => auth()->id(),
        ];

        $iuran = Iuran::create($iuranData);
        $iuran->kartuKeluargas()->sync($data['peserta']);

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil ditambahkan');
    }

    public function update(Request $request, Iuran $iuran)
    {
        $data = $request->validate([
            'nama_iuran'    => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'tipe'          => 'required|in:sekali,mingguan,bulanan',
            'jenis_setoran' => 'required|in:tetap,bebas',
            'nominal'       => 'nullable|integer|min:0',
            'peserta'       => 'required|array',
            'peserta.*'     => 'exists:kartu_keluargas,id',
        ]);

        $iuranData = [
            'nama_iuran'    => $data['nama_iuran'],
            'deskripsi'     => $data['deskripsi'],
            'tipe'          => $data['tipe'],
            'jenis_setoran' => $data['jenis_setoran'],
            'nominal'       => $data['nominal'],
        ];

        $iuran->update($iuranData);
        $iuran->kartuKeluargas()->sync($data['peserta']);

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil diupdate');
    }

    public function destroy(Iuran $iuran)
    {
        $iuran->delete();
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus');
    }
}
