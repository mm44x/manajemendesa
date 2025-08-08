<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\KartuKeluarga;
use App\Models\SetoranIuran;
use Carbon\Carbon;
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

        $iurans = $query->latest()->paginate(10)->appends($request->query());
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

    public function inputSetoran(Request $request, Iuran $iuran)
    {
        // 1. Buat default label periode otomatis sesuai tipe iuran
        if ($iuran->tipe === 'sekali') {
            $defaultLabel = 'Sekali Bayar';
        } elseif ($iuran->tipe === 'mingguan') {
            $minggu = now()->weekOfMonth;
            $bulan = now()->translatedFormat('F');
            $tahun = now()->year;
            $defaultLabel = 'Minggu ke-' . $minggu . ' ' . $bulan . ' ' . $tahun;
        } else { // bulanan
            $defaultLabel = now()->translatedFormat('F Y');
        }

        // --- Prediksi 5 periode berikutnya untuk datalist
        $periode_predict = collect();
        if ($iuran->tipe === 'mingguan') {
            $start = now()->copy();
            for ($i = 0; $i < 5; $i++) {
                // Hitung minggu ke-n untuk tanggal ini di bulan ini
                $minggu = $start->weekOfMonth;
                $bulan = $start->translatedFormat('F');
                $tahun = $start->year;
                $periode_predict->push("Minggu ke-{$minggu} {$bulan} {$tahun}");
                $start->addWeek();
            }
            // Filter agar label unik, jika misal ada tumpukan (minggu ke-5 dst bisa masuk bulan berikut)
            $periode_predict = $periode_predict->unique()->values();
        } elseif ($iuran->tipe === 'bulanan') {
            // Mulai dari bulan saat ini, prediksi 5 bulan ke depan
            $start = now()->copy();
            for ($i = 0; $i < 5; $i++) {
                $periode_predict->push($start->translatedFormat('F Y'));
                $start->addMonth();
            }
        } else {
            $periode_predict = collect(); // kosong untuk 'sekali'
        }


        // 2. Ambil seluruh periode yang sudah ada untuk iuran ini (unique)
        $daftar_periode = \App\Models\SetoranIuran::where('iuran_id', $iuran->id)
            ->orderBy('periode_label')
            ->pluck('periode_label')
            ->unique()
            ->values();

        // 3. Pilih label dari request, custom jika dipilih
        $periode_label = $request->get('periode_label', $defaultLabel);
        if (
            $iuran->tipe !== 'sekali' &&
            $request->has('periode_label_custom') &&
            $request->periode_label === '_custom'
        ) {
            $periode_label = $request->periode_label_custom;
        }

        // 4. Ambil daftar KK peserta beserta status setoran sesuai periode
        $peserta = $iuran->kartuKeluargas()->with(['setoranIurans' => function ($q) use ($periode_label, $iuran) {
            $q->where('periode_label', $periode_label)
                ->where('iuran_id', $iuran->id);
        }])->get();

        return view('iuran.input-setoran', compact(
            'iuran',
            'peserta',
            'periode_label',
            'daftar_periode',
            'periode_predict'
        ));
    }


    public function storeSetoran(Request $request, Iuran $iuran)
    {
        $periode_label = $request->periode_label;
        $ids = $request->input('setor_kk', []);
        $nominals = $request->input('nominal', []);

        // Validasi
        $rules = [
            'periode_label' => 'required|string|max:50',
            'setor_kk'      => 'required|array|min:1',
            'setor_kk.*'    => 'exists:kartu_keluargas,id',
            'nominal'       => 'array'
        ];
        if ($iuran->jenis_setoran === 'tetap') {
            foreach ($ids as $id) {
                $rules["nominal.$id"] = 'nullable|integer|min:0';
            }
        } else {
            foreach ($ids as $id) {
                $rules["nominal.$id"] = 'required|integer|min:1';
            }
        }
        $request->validate($rules);

        foreach ($ids as $kk_id) {
            // Cek jika sudah setor periode ini
            $exists = SetoranIuran::where([
                'iuran_id' => $iuran->id,
                'kartu_keluarga_id' => $kk_id,
                'periode_label' => $periode_label
            ])->exists();
            if ($exists) continue;

            $kk = \App\Models\KartuKeluarga::find($kk_id);
            SetoranIuran::create([
                'iuran_id' => $iuran->id,
                'kartu_keluarga_id' => $kk_id,
                'tanggal_setor' => now(),
                'periode_label' => $periode_label,
                'nominal_dibayar' => $nominals[$kk_id] ?? $iuran->nominal,
                'dibayarkan_oleh' => $kk->kepala_keluarga ?? '-',
                'created_by' => auth()->id(),
            ]);
        }
        return redirect()->route('iuran.setoran.input', [$iuran->id, 'periode_label' => $periode_label])
            ->with('success', 'Setoran berhasil dicatat.');
    }

    public function inputSetoranAjax(Request $request, Iuran $iuran)
    {
        $periode_label = $request->get('periode_label');
        $query = $iuran->kartuKeluargas()->with(['setoranIurans' => function ($q) use ($periode_label, $iuran) {
            $q->where('periode_label', $periode_label)->where('iuran_id', $iuran->id);
        }]);

        // AJAX filter: cari KK, No KK, atau kepala keluarga
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($w) use ($q) {
                $w->where('no_kk', 'like', "%$q%")
                    ->orWhere('kepala_keluarga', 'like', "%$q%");
            });
        }

        $peserta = $query->get();

        // Return hanya isi tabel baris (HTML partial)
        return response()->json([
            'html' => view('iuran._table-setoran', compact('peserta', 'iuran', 'periode_label'))->render()
        ]);
    }
}
