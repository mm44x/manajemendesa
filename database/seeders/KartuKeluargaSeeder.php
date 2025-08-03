<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KartuKeluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class KartuKeluargaSeeder extends Seeder
{
    public function run()
    {
        // Ambil 10 desa acak dari tabel wilayah
        $desas = DB::table('wilayah')
            ->whereRaw("CHAR_LENGTH(REPLACE(kode, '.', '')) = 10")
            ->inRandomOrder()
            ->limit(100)
            ->get();

        foreach ($desas as $i => $desa) {
            KartuKeluarga::create([
                'no_kk' => '3374' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'kepala_keluarga' => 'Bapak ' . Str::random(6),
                'alamat' => 'Jl. Merdeka No. ' . rand(1, 200),
                'rt' => str_pad(rand(1, 9), 3, '0', STR_PAD_LEFT),
                'rw' => str_pad(rand(1, 9), 3, '0', STR_PAD_LEFT),
                'desa_id' => $desa->kode,
                'kode_pos' => rand(52100, 52999),
                'tanggal_terbit' => now()->subDays(rand(10, 1000)),
            ]);
        }
    }
}
