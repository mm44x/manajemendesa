<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KartuKeluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class KartuKeluargaSeeder extends Seeder
{
    public function run()
    {
        $desas = DB::table('wilayah')
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 10")
            ->inRandomOrder()
            ->limit(100)
            ->get();

        foreach ($desas as $i => $desa) {
            $kepala = 'Bapak ' . Str::random(6);

            $kk = KartuKeluarga::create([
                'no_kk' => '3374' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'kepala_keluarga' => $kepala,
                'alamat' => 'Jl. Merdeka No. ' . rand(1, 200),
                'rt' => str_pad(rand(1, 9), 3, '0', STR_PAD_LEFT),
                'rw' => str_pad(rand(1, 9), 3, '0', STR_PAD_LEFT),
                'desa_id' => $desa->kode,
                'kode_pos' => rand(52100, 52999),
                'tanggal_terbit' => now()->subDays(rand(10, 1000)),
            ]);

            AnggotaKeluarga::create([
                'kartu_keluarga_id' => $kk->id,
                'nik' => '3327000000' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'nama' => $kepala,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => substr($desa->kode, 0, 5), // kode kabupaten/kota
                'tanggal_lahir' => now()->subYears(rand(30, 60))->subDays(rand(1, 365)),
                'hubungan' => 'Kepala Keluarga',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'pekerjaan' => 'Wiraswasta',
            ]);
        }
    }
}
