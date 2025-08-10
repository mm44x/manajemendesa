<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Iuran;
use App\Models\SetoranIuran;
use App\Models\KartuKeluarga;
use App\Models\AnggotaKeluarga;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        $summary = null;
        $setoran_terbaru = [];

        if ($role === 'bendahara') {
            // Hitung total KK peserta semua iuran
            $kk_ids = KartuKeluarga::pluck('id')->toArray();
            $bulanIni = now()->month;
            $tahunIni = now()->year;

            $total_setoran_bulan_ini = SetoranIuran::whereMonth('tanggal_setor', $bulanIni)
                ->whereYear('tanggal_setor', $tahunIni)
                ->sum('nominal_dibayar');

            // Partisipasi: berapa KK yang setor minimal satu kali bulan ini
            $kk_sudah_setor = SetoranIuran::whereMonth('tanggal_setor', $bulanIni)
                ->whereYear('tanggal_setor', $tahunIni)
                ->distinct('kartu_keluarga_id')
                ->count('kartu_keluarga_id');
            $total_kk = KartuKeluarga::count();
            $persen_setor = $total_kk ? round(($kk_sudah_setor / $total_kk) * 100) : 0;

            // Total tunggakan bulan ini (hanya iuran tetap dan peserta aktif)
            $total_tunggakan = 0;
            $iurans = Iuran::with('kartuKeluargas')->get();
            foreach ($iurans as $iuran) {
                if ($iuran->jenis_setoran == 'tetap') {
                    foreach ($iuran->kartuKeluargas as $kk) {
                        $sudah = SetoranIuran::where('iuran_id', $iuran->id)
                            ->where('kartu_keluarga_id', $kk->id)
                            ->whereMonth('tanggal_setor', $bulanIni)
                            ->whereYear('tanggal_setor', $tahunIni)
                            ->exists();
                        if (!$sudah) $total_tunggakan += $iuran->nominal ?? 0;
                    }
                }
            }

            $summary = [
                'nominal_setoran_bulan_ini' => $total_setoran_bulan_ini,
                'tunggakan_bulan_ini' => $total_tunggakan,
                'persen_setor' => $persen_setor,
                'iuran_aktif' => Iuran::where('tipe', '!=', 'selesai')->count(),
            ];

            $setoran_terbaru = SetoranIuran::with('kartuKeluarga')
                ->orderByDesc('tanggal_setor')
                ->limit(8)
                ->get();

            $bulan = collect();
            $nominal_per_bulan = collect();

            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $label = $date->translatedFormat('M Y'); // ex: 'Agu 2024'
                $bulan->push($label);

                $total = DB::table('setoran_iurans')
                    ->whereMonth('tanggal_setor', $date->month)
                    ->whereYear('tanggal_setor', $date->year)
                    ->sum('nominal_dibayar');
                $nominal_per_bulan->push($total);
            }

            return view('dashboard', compact(
                'role',
                'summary',
                'setoran_terbaru',
                'bulan',
                'nominal_per_bulan'
            ));
        }
        if ($role === 'sekretaris') {
            $totalKK = KartuKeluarga::count();
            $totalAnggota = AnggotaKeluarga::count();
            $kkBaruBulanIni = KartuKeluarga::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $kkTanpaAnggota = KartuKeluarga::doesntHave('anggotaKeluargas')->count();

            $summary = [
                'total_kk' => $totalKK,
                'total_anggota' => $totalAnggota,
                'kk_baru_bulan_ini' => $kkBaruBulanIni,
                'kk_tanpa_anggota' => $kkTanpaAnggota
            ];

            $kk_terbaru = KartuKeluarga::orderByDesc('created_at')->limit(10)->get();
            $anggota_terbaru = AnggotaKeluarga::with('kartuKeluarga')->orderByDesc('created_at')->limit(10)->get();

            return view('dashboard', compact('role', 'summary', 'kk_terbaru', 'anggota_terbaru'));
        }

        if ($role === 'admin') {
            $totalUsers = User::count();
            $usersPerRole = User::select('role', DB::raw('COUNT(*) as c'))->groupBy('role')->pluck('c', 'role');

            $totalKK = KartuKeluarga::count();
            $totalAnggota = AnggotaKeluarga::count();
            $totalIuran = Iuran::count();

            $bulanIni = now()->month;
            $tahunIni = now()->year;
            $nominalSetoranBulanIni = SetoranIuran::whereMonth('tanggal_setor', $bulanIni)
                ->whereYear('tanggal_setor', $tahunIni)
                ->sum('nominal_dibayar');

            $kkTerbaru = KartuKeluarga::orderByDesc('created_at')->limit(8)->get();
            $iuranTerbaru = Iuran::orderByDesc('created_at')->limit(8)->get();

            return view('dashboard', compact(
                'role',
                'totalUsers',
                'usersPerRole',
                'totalKK',
                'totalAnggota',
                'totalIuran',
                'nominalSetoranBulanIni',
                'kkTerbaru',
                'iuranTerbaru'
            ));
        }
        return view('dashboard', compact('role'));
    }
}
