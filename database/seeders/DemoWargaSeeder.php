<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KartuKeluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoWargaSeeder extends Seeder
{
    public function run()
    {
        // Data referensi sederhana untuk nama & pekerjaan
        $namaDepan = ['Budi', 'Agus', 'Slamet', 'Dedi', 'Andi', 'Joko', 'Tono', 'Suwandi', 'Rudi', 'Suyanto', 'Eka', 'Teguh'];
        $namaBelakang = ['Sutrisno', 'Saputra', 'Susilo', 'Santoso', 'Wijaya', 'Rahman', 'Gunawan', 'Subagyo', 'Fadli', 'Hidayat'];
        $pekerjaan = ['Wiraswasta', 'Petani', 'Guru', 'PNS', 'Karyawan Swasta', 'Buruh', 'Pedagang', 'Sopir', 'Nelayan'];

        $desas = DB::table('wilayah')
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 10")
            ->inRandomOrder()
            ->limit(100)
            ->get();

        foreach ($desas as $i => $desa) {
            // Kepala keluarga
            $kepala = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];

            $kk = KartuKeluarga::create([
                'no_kk' => '3374' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'kepala_keluarga' => $kepala,
                'alamat' => 'Jl. ' . Str::ucfirst(Str::random(5)) . ' No. ' . rand(1, 200),
                'rt' => str_pad(rand(1, 15), 3, '0', STR_PAD_LEFT),
                'rw' => str_pad(rand(1, 10), 3, '0', STR_PAD_LEFT),
                'desa_id' => $desa->kode,
                'kode_pos' => rand(52100, 52999),
                'tanggal_terbit' => now()->subDays(rand(10, 1000)),
            ]);

            // Anggota 1 (kepala keluarga)
            AnggotaKeluarga::create([
                'kartu_keluarga_id' => $kk->id,
                'nik' => '3327' . str_pad(rand(1, 999999999), 12, '0', STR_PAD_LEFT),
                'nama' => $kepala,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => substr($desa->kode, 0, 5),
                'tanggal_lahir' => now()->subYears(rand(35, 55))->subDays(rand(1, 365)),
                'hubungan' => 'Kepala Keluarga',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'pekerjaan' => $pekerjaan[array_rand($pekerjaan)],
            ]);

            // Anggota 2 (istri)
            $namaIstri = 'Ibu ' . $namaBelakang[array_rand($namaBelakang)];
            AnggotaKeluarga::create([
                'kartu_keluarga_id' => $kk->id,
                'nik' => '3328' . str_pad(rand(1, 999999999), 12, '0', STR_PAD_LEFT),
                'nama' => $namaIstri,
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => substr($desa->kode, 0, 5),
                'tanggal_lahir' => now()->subYears(rand(30, 50))->subDays(rand(1, 365)),
                'hubungan' => 'Istri',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'pekerjaan' => 'Ibu Rumah Tangga',
            ]);

            // Anggota anak-anak (1-4 anak)
            $jumlahAnak = rand(1, 4);
            for ($j = 1; $j <= $jumlahAnak; $j++) {
                $jk = rand(0, 1) ? 'Laki-laki' : 'Perempuan';
                $namaAnak = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
                AnggotaKeluarga::create([
                    'kartu_keluarga_id' => $kk->id,
                    'nik' => '3329' . str_pad(rand(1, 999999999), 12, '0', STR_PAD_LEFT),
                    'nama' => $namaAnak,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => substr($desa->kode, 0, 5),
                    'tanggal_lahir' => now()->subYears(rand(3, 25))->subDays(rand(1, 365)),
                    'hubungan' => 'Anak',
                    'agama' => 'Islam',
                    'pendidikan' => rand(0, 1) ? 'SMP' : 'SD',
                    'pekerjaan' => rand(0, 1) ? 'Pelajar' : null,
                ]);
            }
        }
    }
}
