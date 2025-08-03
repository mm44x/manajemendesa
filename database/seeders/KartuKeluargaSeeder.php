<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KartuKeluarga;


class KartuKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    KartuKeluarga::create([
        'no_kk' => '3374123456789001',
        'kepala_keluarga' => 'Budi Santoso',
        'alamat' => 'Jl. Melati No. 12',
        'rt' => '001',
        'rw' => '003',
        'desa' => 'Desa Mulyo',
        'kecamatan' => 'Pemalang',
        'kabupaten' => 'Pemalang',
        'kode_pos' => '52361',
        'tanggal_terbit' => now()
    ]);
    }
}
